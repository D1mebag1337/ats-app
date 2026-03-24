<template>
    <div class="overview-page">

        <!-- HERO IMAGES -->
        <section class="hero-images">
            <div
                v-for="stelle in heroStellen"
                :key="stelle.StellenID"
                class="hero-image-slot"
            >
                <img
                    v-if="stelle.ImageID"
                    :src="`/images/${stelle.ImageID}`"
                    :alt="stelle.Name"
                />
                <div v-else class="hero-image-placeholder" />
            </div>
            <!-- Fill remaining slots with placeholders if fewer than 3 jobs -->
            <div
                v-for="n in Math.max(0, 3 - heroStellen.length)"
                :key="`placeholder-${n}`"
                class="hero-image-slot"
            >
                <div class="hero-image-placeholder" />
            </div>
        </section>

        <!-- HEADLINE + JOB GRID -->
        <section class="jobs-section">
            <h1 class="jobs-headline">Level Up – Probier was Neues.</h1>

            <div v-if="stellen.length > 0" class="jobs-grid">
                <Link
                    v-for="stelle in stellen"
                    :key="stelle.StellenID"
                    :href="`/jobs/${stelle.StellenID}`"
                    class="job-card"
                >
                    <h2 class="job-title">{{ stelle.Name }}</h2>
                    <p class="job-location">Ort: {{ stelle.Arbeitsorte }}</p>
                    <p class="job-description">{{ stelle.Kurzbeschreibung }}</p>
                </Link>
            </div>

            <p v-else class="no-jobs">Aktuell sind keine Stellen ausgeschrieben.</p>
        </section>

    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    stellen: {
        type: Array,
        default: () => [],
    },
})

// Use the first 3 jobs that have an image for the hero section
const heroStellen = computed(() =>
    props.stellen.filter(s => s.ImageID).slice(0, 3)
)
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');

.overview-page {
    font-family: 'Source Serif 4', Georgia, serif;
    color: #1a1a1a;
    background-color: #2d7a2d;
    min-height: 100vh;
}

/* ── Hero Images ── */
.hero-images {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    padding: 2rem 2rem 0;
}

.hero-image-slot {
    aspect-ratio: 3 / 4;
    overflow: hidden;
    border-radius: 12px;
}

.hero-image-slot img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.hero-image-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 12px;
}

/* ── Jobs Section ── */
.jobs-section {
    padding: 2.5rem 2rem 4rem;
}

.jobs-headline {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    text-align: center;
    letter-spacing: -0.01em;
    margin: 0 0 2.5rem;
}

/* ── Job Grid ── */
.jobs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

/* ── Job Card ── */
.job-card {
    background: #e8e8e8;
    border-radius: 16px;
    padding: 1.75rem 1.5rem;
    text-decoration: none;
    color: inherit;
    display: block;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.job-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.job-title {
    font-family: 'Oswald', sans-serif;
    font-size: 1.15rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #1a1a1a;
    margin: 0 0 0.5rem;
    line-height: 1.2;
}

.job-location {
    font-family: 'Oswald', sans-serif;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #1a1a1a;
    margin: 0 0 1.25rem;
}

.job-description {
    font-size: 0.92rem;
    line-height: 1.65;
    color: #3a3a3a;
    font-weight: 300;
    margin: 0;
}

/* ── No Jobs ── */
.no-jobs {
    text-align: center;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    margin-top: 3rem;
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .jobs-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .hero-images {
        gap: 1rem;
        padding: 1.5rem 1.5rem 0;
    }
}

@media (max-width: 600px) {
    .hero-images {
        grid-template-columns: 1fr;
    }

    .jobs-grid {
        grid-template-columns: 1fr;
    }

    .jobs-section {
        padding: 2rem 1.25rem 3rem;
    }
}
</style>
