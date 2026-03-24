<template>
    <div class="auth-page">

        <PublicNavbar />

        <div class="auth-wrapper">

            <!-- LOGIN CARD -->
            <Transition name="card-swap" mode="out-in">
            <section v-if="!showRegister" class="auth-card" key="login">
                <h2 class="auth-card-title">Anmelden</h2>
                <p class="auth-card-subtitle">Willkommen zurück.</p>

                <div v-if="loginErrors._" class="auth-error">{{ loginErrors._ }}</div>

                <form @submit.prevent="submitLogin" class="auth-form">
                    <div class="field">
                        <label for="login-email">E-Mail</label>
                        <input
                            id="login-email"
                            v-model="loginForm.email"
                            type="email"
                            placeholder="email@beispiel.de"
                            required
                            autocomplete="email"
                        />
                        <span v-if="loginErrors.email" class="field-error">{{ loginErrors.email }}</span>
                    </div>

                    <div class="field">
                        <label for="login-password">Passwort</label>
                        <input
                            id="login-password"
                            v-model="loginForm.password"
                            type="password"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        />
                        <span v-if="loginErrors.password" class="field-error">{{ loginErrors.password }}</span>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="auth-btn auth-btn--dark" :disabled="loginProcessing">
                            {{ loginProcessing ? 'Wird angemeldet…' : 'Anmelden' }}
                        </button>
                        <button type="button" class="auth-btn auth-btn--outline" @click="toggleRegister">
                            {{ showRegister ? 'Abbrechen' : 'Registrieren' }}
                        </button>
                    </div>
                </form>
            </section>

            <!-- REGISTER CARD -->
                <section v-else class="auth-card auth-card--register" key="register">
                    <h2 class="auth-card-title">Konto erstellen</h2>
                    <p class="auth-card-subtitle">Registriere dich, um dich schnell und einfach zu bewerben.</p>

                    <form @submit.prevent="submitRegister" class="auth-form">

                        <div class="field-row">
                            <div class="field">
                                <label for="reg-vorname">Vorname</label>
                                <input
                                    id="reg-vorname"
                                    v-model="registerForm.Vorname"
                                    type="text"
                                    placeholder="Max"
                                    required
                                />
                                <span v-if="registerErrors.Vorname" class="field-error">{{ registerErrors.Vorname }}</span>
                            </div>
                            <div class="field">
                                <label for="reg-name">Nachname</label>
                                <input
                                    id="reg-name"
                                    v-model="registerForm.Name"
                                    type="text"
                                    placeholder="Mustermann"
                                    required
                                />
                                <span v-if="registerErrors.Name" class="field-error">{{ registerErrors.Name }}</span>
                            </div>
                        </div>

                        <div class="field">
                            <label for="reg-email">E-Mail</label>
                            <input
                                id="reg-email"
                                v-model="registerForm.email"
                                type="email"
                                placeholder="email@beispiel.de"
                                required
                                autocomplete="email"
                            />
                            <span v-if="registerErrors.email" class="field-error">{{ registerErrors.email }}</span>
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label for="reg-password">Passwort</label>
                                <input
                                    id="reg-password"
                                    v-model="registerForm.password"
                                    type="password"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />
                                <span v-if="registerErrors.password" class="field-error">{{ registerErrors.password }}</span>
                            </div>
                            <div class="field">
                                <label for="reg-password-confirm">Passwort bestätigen</label>
                                <input
                                    id="reg-password-confirm"
                                    v-model="registerForm.password_confirmation"
                                    type="password"
                                    placeholder="••••••••"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>
                        </div>

                        <div class="field">
                            <label for="reg-gebdatum">Geburtsdatum</label>
                            <input
                                id="reg-gebdatum"
                                v-model="registerForm.Gebdatum"
                                type="date"
                                required
                            />
                            <span v-if="registerErrors.Gebdatum" class="field-error">{{ registerErrors.Gebdatum }}</span>
                        </div>

                        <div class="field-row field-row--street">
                            <div class="field">
                                <label for="reg-strasse">Straße</label>
                                <input
                                    id="reg-strasse"
                                    v-model="registerForm.Strasse"
                                    type="text"
                                    placeholder="Musterstraße"
                                    required
                                />
                                <span v-if="registerErrors.Strasse" class="field-error">{{ registerErrors.Strasse }}</span>
                            </div>
                            <div class="field field--narrow">
                                <label for="reg-hausnr">Nr.</label>
                                <input
                                    id="reg-hausnr"
                                    v-model="registerForm.Hausnr"
                                    type="text"
                                    placeholder="12a"
                                    required
                                />
                                <span v-if="registerErrors.Hausnr" class="field-error">{{ registerErrors.Hausnr }}</span>
                            </div>
                        </div>

                        <div class="field-row field-row--city">
                            <div class="field field--narrow">
                                <label for="reg-plz">PLZ</label>
                                <input
                                    id="reg-plz"
                                    v-model="registerForm.Plz"
                                    type="text"
                                    inputmode="numeric"
                                    placeholder="12345"
                                    required
                                />
                                <span v-if="registerErrors.Plz" class="field-error">{{ registerErrors.Plz }}</span>
                            </div>
                            <div class="field">
                                <label for="reg-ort">Ort</label>
                                <input
                                    id="reg-ort"
                                    v-model="registerForm.Ort"
                                    type="text"
                                    placeholder="Berlin"
                                    required
                                />
                                <span v-if="registerErrors.Ort" class="field-error">{{ registerErrors.Ort }}</span>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="auth-btn auth-btn--dark" :disabled="registerProcessing">
                                {{ registerProcessing ? 'Konto wird erstellt…' : 'Konto erstellen' }}
                            </button>
                            <button type="button" class="auth-btn auth-btn--outline" @click="toggleRegister">
                                Zurück zum Login
                            </button>
                        </div>
                    </form>
                </section>
            </Transition>

        </div>

    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import PublicNavbar from '@/components/PublicNavbar.vue'

const showRegister = ref(false)

function toggleRegister() {
    showRegister.value = !showRegister.value
}

// ── Login ──────────────────────────────────────────────
const loginForm = reactive({ email: '', password: '' })
const loginErrors = reactive({})
const loginProcessing = ref(false)

function submitLogin() {
    loginProcessing.value = true
    Object.keys(loginErrors).forEach(k => delete loginErrors[k])

    router.post('/auth/login', loginForm, {
        onError: (errors) => Object.assign(loginErrors, errors),
        onFinish: () => { loginProcessing.value = false },
    })
}

// ── Register ───────────────────────────────────────────
const registerForm = reactive({
    email: '',
    password: '',
    password_confirmation: '',
    Vorname: '',
    Name: '',
    Gebdatum: '',
    Strasse: '',
    Hausnr: '',
    Plz: '',
    Ort: '',
})
const registerErrors = reactive({})
const registerProcessing = ref(false)

function submitRegister() {
    registerProcessing.value = true
    Object.keys(registerErrors).forEach(k => delete registerErrors[k])

    router.post('/auth/register', registerForm, {
        onError: (errors) => Object.assign(registerErrors, errors),
        onFinish: () => { registerProcessing.value = false },
    })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');

.auth-page {
    min-height: 100vh;
    background-color: #2d7a2d;
    font-family: 'Source Serif 4', Georgia, serif;
}

/* ── Centered single-column wrapper ── */
.auth-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    padding: 3rem 1.25rem 5rem;
}

/* ── Card ── */
.auth-card {
    background: #f0f0f0;
    border-radius: 20px;
    padding: 2.5rem 2.25rem;
    width: 100%;
    max-width: 480px;
}

.auth-card--register {
    max-width: 560px;
}

.auth-card-title {
    font-family: 'Oswald', sans-serif;
    font-size: 1.6rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #1a1a1a;
    margin: 0 0 0.3rem;
}

.auth-card-subtitle {
    font-size: 0.92rem;
    font-weight: 300;
    color: #555;
    margin: 0 0 2rem;
}

/* ── Form ── */
.auth-form {
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.85rem;
}

.field-row--street {
    grid-template-columns: 1fr 90px;
}

.field-row--city {
    grid-template-columns: 90px 1fr;
}

.field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}

.field label {
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #444;
}

.field input {
    background: #ffffff;
    border: 1.5px solid #d0d0d0;
    border-radius: 8px;
    padding: 0.6rem 0.85rem;
    font-family: 'Source Serif 4', serif;
    font-size: 0.92rem;
    color: #1a1a1a;
    outline: none;
    transition: border-color 0.15s ease;
    width: 100%;
    box-sizing: border-box;
}

.field input:focus {
    border-color: #2d7a2d;
}

.field-error {
    font-size: 0.78rem;
    color: #c0392b;
}

.auth-error {
    background: #fdecea;
    border: 1px solid #f5c6c2;
    border-radius: 8px;
    padding: 0.65rem 1rem;
    font-size: 0.88rem;
    color: #c0392b;
    margin-bottom: 1.25rem;
}

/* ── Buttons row ── */
.form-actions {
    display: flex;
    gap: 0.75rem;
    margin-top: 0.5rem;
    flex-wrap: wrap;
}

.auth-btn {
    font-family: 'Oswald', sans-serif;
    font-size: 0.92rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    padding: 0.72rem 1.75rem;
    border-radius: 999px;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease, transform 0.15s ease, opacity 0.15s;
}

.auth-btn--dark {
    background: #1a1a1a;
    color: #ffffff;
}

.auth-btn--dark:hover:not(:disabled) {
    background: #333;
    transform: translateY(-1px);
}

.auth-btn--outline {
    background: transparent;
    color: #1a1a1a;
    border: 1.5px solid #1a1a1a;
}

.auth-btn--outline:hover {
    background: rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}

.auth-btn:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

/* ── Card swap transition ── */
.card-swap-enter-active,
.card-swap-leave-active {
    transition: opacity 0.2s ease, transform 0.25s ease;
}

.card-swap-enter-from {
    opacity: 0;
    transform: translateY(16px);
}

.card-swap-leave-to {
    opacity: 0;
    transform: translateY(-16px);
}

/* ── Responsive ── */
@media (max-width: 600px) {
    .auth-card {
        padding: 2rem 1.5rem;
    }

    .field-row,
    .field-row--street,
    .field-row--city {
        grid-template-columns: 1fr;
    }
}
</style>
