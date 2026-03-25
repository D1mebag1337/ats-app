<template>
    <div class="navbar-wrapper">
        <nav class="navbar">

            <!-- Logged OUT: brand on the left -->
            <template v-if="!user">
                <Link href="/" class="navbar-brand">
                    <span class="brand-mark"></span>
                    <span class="brand-name">ATS Web-Application</span>
                </Link>
                <span class="navbar-spacer"></span>
                <Link href="/auth" class="nav-btn nav-btn--dark">Login</Link>
            </template>

            <!-- Logged IN: greeting + role actions + logout -->
            <template v-else>
                <span class="navbar-greeting">
                    Hallo <strong>{{ displayName }}</strong>
                </span>

                <div class="navbar-actions">
                    <Link href="/dashboard" class="nav-btn nav-btn--outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor" style="display:block"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </Link>
                    <!-- Recruiter-only buttons -->
                    <template v-if="isRecruiter">
                        <Link href="/stellen" class="nav-btn nav-btn--outline">Stellen editieren</Link>
                        <Link href="/stellen/create" class="nav-btn nav-btn--outline">Neue Stelle</Link>
                    </template>
                    <!-- Applicant-only button -->
                    <template v-if="isBewerber">
                        <Link href="/bewerbungen/create" class="nav-btn nav-btn--outline">Bewerbung erstellen</Link>
                    </template>
                </div>

                <form method="POST" action="/auth/logout" @submit.prevent="logout">
                    <button type="submit" class="nav-btn nav-btn--dark">Ausloggen</button>
                </form>
            </template>

        </nav>
    </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)
const displayName = computed(() => page.props.auth?.displayName ?? page.props.auth?.user?.Email ?? '')
const role = computed(() => page.props.auth?.role ?? null)
const isRecruiter = computed(() => role.value === 'R' || role.value === 'H')
const isBewerber  = computed(() => role.value === 'B')


function logout() {
    router.post('/auth/logout')
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:wght@400;600&display=swap');

.navbar-wrapper {
    position: sticky;
    top: 0;
    z-index: 100;
    background-color: #2d7a2d;
    padding: 0.6rem 1.25rem 0rem;
}

.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1.5rem;
    height: 56px;
    background-color: #e8e8e8;
    border-radius: 999px;
}

.navbar-brand {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    text-decoration: none;
}

.brand-mark {
    display: block;
    width: 26px;
    height: 26px;
    border-radius: 6px;
    background: #1a1a1a;
}

.brand-name {
    font-family: 'Oswald', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #1a1a1a;
}

.navbar-greeting {
    font-family: 'Source Serif 4', serif;
    font-size: 0.95rem;
    color: #1a1a1a;
    white-space: nowrap;
}

.navbar-greeting strong {
    font-weight: 700;
}

.navbar-spacer {
    flex: 1;
}

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    flex: 1;
    justify-content: center;
}

.nav-btn {
    font-family: 'Source Serif 4', serif;
    font-size: 0.88rem;
    font-weight: 600;
    text-decoration: none;
    padding: 0.4rem 1.1rem;
    border-radius: 999px;
    transition: opacity 0.15s ease, transform 0.15s ease;
    white-space: nowrap;
    cursor: pointer;
    background: none;
}

.nav-btn--outline {
    background: transparent;
    color: #1a1a1a;
    border: 1.5px solid #1a1a1a;
}

.nav-btn--outline:hover {
    background: rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}

.nav-btn--dark {
    background: #1a1a1a;
    color: #ffffff;
    border: none;
}

.nav-btn--dark:hover {
    opacity: 0.85;
    transform: translateY(-1px);
}
</style>
