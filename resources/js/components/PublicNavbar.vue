<template>
    <div class="navbar-wrapper">
        <nav class="navbar">
            <Link href="/" class="navbar-brand">
                <span class="brand-mark"></span>
                <span class="brand-name">ATS Web-Application</span>
            </Link>

            <div class="navbar-actions">
                <template v-if="user">
                    <Link href="/dashboard" class="nav-btn nav-btn--outline">Dashboard</Link>
                </template>
                <template v-else>
                    <Link href="/auth" class="nav-btn nav-btn--dark">Login</Link>
                </template>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)
const appName = computed(() => page.props.name ?? 'ATS')
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:wght@400;600&display=swap');

/* Outer wrapper stays sticky and provides the green backdrop */
.navbar-wrapper {
    position: sticky;
    top: 0;
    z-index: 100;
    background-color: #2d7a2d;
    padding: 0.6rem 1.25rem 0rem;
}

/* The pill itself */
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

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 0.65rem;
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
}

/* Outlined style — matches the middle buttons in the image */
.nav-btn--outline {
    background: transparent;
    color: #1a1a1a;
    border: 1.5px solid #1a1a1a;
}

.nav-btn--outline:hover {
    background: rgba(0, 0, 0, 0.06);
    transform: translateY(-1px);
}

/* Dark filled — matches the "Ausloggen" button on the right */
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
