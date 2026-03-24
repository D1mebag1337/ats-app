# CLAUDE.md — ats-app

This file gives Claude Code persistent context about this project.
Read it before making any changes or suggestions.

---

## Project Overview

**ats-app** is a web-based Applicant Tracking System (ATS).
Recruiters manage job postings and review incoming applications.
Candidates register, build a profile, and apply to open positions by uploading their documents.

---

## Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Backend     | PHP 8.x · Laravel (latest stable)   |
| Frontend    | Vue.js 3 · Inertia.js               |
| Database    | PostgreSQL                          |
| Dev server  | Laravel Sail (Docker) + Vite (HMR)  |

Inertia.js bridges Laravel and Vue — **there is no separate REST/JSON API**.
All data is passed from controllers to Vue pages via Inertia props.

---

## Dev Environment

The project runs inside Docker via **Laravel Sail**.
Always prefix PHP/Artisan/Composer commands with `sail` (aliased globally — no full path needed).
Frontend assets are served by Vite with hot-module replacement.

### Common commands

```bash
# Start all containers (detached)
sail up -d

# Stop containers
sail down

# Run Artisan commands
sail artisan <command>

# Run Composer commands
sail composer <command>

# Frontend dev server (hot reload)
sail npm run dev

# Frontend production build
sail npm run build

# Run tests
sail artisan test

# Run a specific test file
sail artisan test --filter <TestName>
```

> Never run `php artisan` or `npm` directly on the host — always use `sail`.

---

## Architecture

Simple **MVC** architecture using Laravel conventions.

```
app/
  Http/
    Controllers/       # One controller per domain entity
    Middleware/
  Models/              # Eloquent models (see Domain Entities below)
  Policies/            # Authorization per model
resources/
  js/
    Pages/             # Vue page components (rendered by Inertia)
    Components/        # Reusable Vue components
    Layouts/           # App shell layouts
routes/
  web.php              # All routes (no api.php needed)
database/
  migrations/          # Already completed — do not modify without discussion
  seeders/
```

---

## Domain Entities & Data Model

### User
Authentication and role management.

| Column  | Type         | Notes                        |
|---------|--------------|------------------------------|
| UserID  | smallint PK  |                              |
| Role    | varchar(1)   | e.g. `A` = Admin/Recruiter, `B` = Bewerber/Candidate |
| Email   | text         |                              |
| PW      | text         | Hashed via Laravel's bcrypt  |

### Bewerber (Candidate Profile)
One-to-one with User. Created when a user with Role = candidate registers.

| Column    | Type        |
|-----------|-------------|
| UserID    | smallint FK/PK (from User) |
| Name      | text        |
| Vorname   | text        |
| Gebdatum  | date        |
| Strasse   | text        |
| Hausnr    | text        |
| Plz       | smallint    |
| Ort       | text        |

### Stelle (Job Posting)
Created and managed by recruiters.

| Column           | Type        | Notes              |
|------------------|-------------|--------------------|
| StellenID        | smallint PK |                    |
| Name             | text        |                    |
| Beschreibung     | text        |                    |
| Kurzbeschreibung | text        |                    |
| Aufgaben         | json        |                    |
| Voraussetzungen  | json        |                    |
| ImageID          | smallint FK | → Images           |
| Online           | boolean     | Visibility toggle  |
| Arbeitsorte      | text        |                    |

### Bewerbung (Application)
Join between Bewerber and Stelle. Stores uploaded documents as binary.

| Column                | Type         | Notes                          |
|-----------------------|--------------|--------------------------------|
| BewerbungID           | smallint PK  |                                |
| UserID                | smallint FK  | → User (Bewerber)              |
| StellenID             | smallint FK  | → Stelle                       |
| Anschreiben           | bytea        | Cover letter                   |
| Lebenslauf            | bytea        | CV / Resume                    |
| Zeugnisse             | bytea        | References / certificates      |
| Zertifikate           | bytea        | Additional certificates        |
| Datenschutzerklaerung | boolean      | GDPR consent flag              |
| Status                | varchar(50)  | e.g. `pending`, `reviewed`, `rejected`, `accepted` |

### Images
Stores images for job postings as binary.

| Column        | Type        |
|---------------|-------------|
| ImageID       | smallint PK |
| Alternativtext| text        |
| ImageBinary   | bytea       |

---

## Relationships

```
User ──1:1── Bewerber
User ──1:n── Bewerbung
Stelle ──1:n── Bewerbung
Stelle ──n:1── Images
```

---

## Conventions

### Git — Conventional Commits
All commit messages must follow the [Conventional Commits](https://www.conventionalcommits.org/) spec:

```
<type>(optional scope): <short description>

Types: feat | fix | docs | style | refactor | test | chore
```

Examples:
```
feat(bewerbung): add status update endpoint
fix(auth): correct role check for recruiter middleware
refactor(stelle): extract job visibility toggle to service method
docs: update CLAUDE.md with Images entity
```

### PHP / Laravel
- Follow Laravel naming conventions (PascalCase models, snake_case columns, kebab-case routes)
- Controllers stay thin — business logic belongs in Service classes if it grows complex
- Use Laravel Form Requests for validation
- Use Laravel Policies for authorization (role-based: recruiter vs. candidate)
- Migrations are **done** — do not create new migrations without explicit instruction

### Vue / Inertia
- Vue 3 Composition API (`<script setup>`) preferred
- Page components live in `resources/js/Pages/`
- Reusable UI pieces live in `resources/js/Components/`
- Pass data from controllers via Inertia `->with()` or `Inertia::render()` props — no axios calls

### Database
- Database is **PostgreSQL** — avoid MySQL-specific syntax
- Binary document files are stored directly as `bytea` columns in `Bewerbung`
- Migrations are complete — the schema matches the ERM exactly

---

## Key Things to Remember

- **No API routes** — everything goes through `routes/web.php` with Inertia responses
- **Sail for all commands** — never run artisan or npm on the host
- **Migrations are frozen** — the schema is done, work within it
- **Role-based access** — always check `User.Role` before allowing recruiter actions
- **GDPR flag** — `Datenschutzerklaerung` must be `true` before a `Bewerbung` can be saved
