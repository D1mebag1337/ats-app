<template>
    <div class="landing-page">

        <PublicNavbar />

        <!-- HERO SECTION -->
        <section class="hero">
            <div class="hero-image">
                <img src="landingPage.png" alt="Team working together" />
            </div>
            <div class="hero-content">
                <h1 class="hero-title">Bereit für den nächsten Schritt?</h1>
                <p class="hero-subtitle">Dann bewirb dich jetzt!</p>
                <a href="#stellenanzeigen" class="hero-btn" @click.prevent="scrollToJobs">Zu den Stellenanzeigen</a>
            </div>
        </section>

        <!-- WHY US SECTION -->
        <section class="why-us">
            <h2 class="why-us-title">Warum bei uns durchstarten?</h2>
            <div class="features-grid">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="feature-card"
                    :style="{ animationDelay: `${index * 0.15}s` }"
                >
                    <div class="feature-accent"></div>
                    <h3 class="feature-title">{{ feature.title }}</h3>
                    <p class="feature-text">{{ feature.text }}</p>
                </div>
            </div>
        </section>

        <!-- JOB OVERVIEW SECTION -->
        <section id="stellenanzeigen" class="jobs-section">

            <!-- Hero images from the first 3 jobs -->
            <div class="jobs-hero-images">
                <div
                    v-for="stelle in heroStellen"
                    :key="stelle.StellenID"
                    class="jobs-hero-slot"
                >
                    <img
                        v-if="stelle.ImageID"
                        :src="`/images/${stelle.ImageID}`"
                        :alt="stelle.Name"
                    />
                    <div v-else class="jobs-hero-placeholder" />
                </div>
                <div
                    v-for="n in Math.max(0, 3 - heroStellen.length)"
                    :key="`ph-${n}`"
                    class="jobs-hero-slot"
                >
                    <div class="jobs-hero-placeholder" />
                </div>
            </div>

            <h2 class="jobs-headline">Level Up – Probier was Neues.</h2>

            <div v-if="stellen.length > 0" class="jobs-grid">
                <Link
                    v-for="stelle in stellen"
                    :key="stelle.StellenID"
                    :href="`/jobs/${stelle.StellenID}`"
                    class="job-card"
                >
                    <h3 class="job-title">{{ stelle.Name }}</h3>
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
import PublicNavbar from '@/components/PublicNavbar.vue'

const props = defineProps({
    stellen: {
        type: Array,
        default: () => [],
    },
})

const features = [
    {
        title: 'Modernes Arbeitsumfeld',
        text: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accus',
    },
    {
        title: 'Starkes Team',
        text: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accus',
    },
    {
        title: 'Beste Chancen',
        text: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accus',
    },
]

const heroStellen = computed(() =>
    props.stellen.filter(s => s.ImageID).slice(0, 3)
)

function scrollToJobs() {
    document.getElementById('stellenanzeigen')?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<style scoped>
/* ── Google Font ── */
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');


/* ── Page ── */
.landing-page {
    font-family: 'Source Serif 4', Georgia, serif;
    color: #1a1a1a;
}

/* ── HERO ── */
.hero {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: 480px;
    background-color: #2d7a2d;
    overflow: hidden;
}

.hero-image {
    position: relative;
    overflow: hidden;
    padding: 4rem;
}

.hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    border-radius: 12px;
    animation: imageReveal 1s ease-out forwards;
}
@keyframes imageReveal {
    from { clip-path: inset(0 100% 0 0); }
    to   { clip-path: inset(0 0% 0 0); }
}

.hero-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 3rem 4rem 3rem 3.5rem;
    animation: fadeUp 0.8s ease-out 0.3s both;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}

.hero-title {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(2.4rem, 4vw, 3.6rem);
    font-weight: 700;
    line-height: 1.45;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: -0.01em;
    margin: 0 0 1rem 0;
}

.hero-subtitle {
    font-size: 1.25rem;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.88);
    margin: 0 0 2.5rem 0;
    font-style: italic;
}

.hero-btn {
    display: inline-block;
    align-self: flex-start;
    background: #1a1a1a;
    color: #ffffff;
    font-family: 'Source Serif 4', serif;
    font-size: 0.95rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    padding: 0.8rem 1.8rem;
    border-radius: 999px;
    text-decoration: none;
    transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 4px 16px rgba(0,0,0,0.25);
    cursor: pointer;
}

.hero-btn:hover {
    background: #1f5c1f;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}

/* ── WHY US ── */
.why-us {
    background: linear-gradient(to bottom, #2d7a2d 30%, #ffffff 30%);
    padding: 1.5rem 5vw 5rem;
}

.why-us-title {
    font-family: 'Source Serif 4', serif;
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 600;
    color: #ffffff;
    text-align: center;
    margin: 0 0 3.5rem 0;
    letter-spacing: -0.01em;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
    max-width: 1100px;
    margin: 0 auto;
}

.feature-card {
    position: relative;
    padding: 0.25rem 0 0 0;
    animation: fadeUp 0.6s ease-out both;
}

.feature-accent {
    width: 2.5rem;
    height: 3px;
    background: #2d7a2d;
    margin-bottom: 1rem;
    border-radius: 2px;
}

.feature-title {
    font-family: 'Oswald', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #1a1a1a;
    margin: 0 0 1.25rem 0;
}

.feature-text {
    font-size: 0.92rem;
    line-height: 1.75;
    color: #3a3a3a;
    font-weight: 300;
    margin: 0;
}

/* ── JOBS SECTION ── */
.jobs-section {
    background-color: #2d7a2d;
    padding: 0 0 4rem;
    scroll-margin-top: 1rem;
}

.jobs-hero-images {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    padding: 2rem 2rem 0;
}

.jobs-hero-slot {
    aspect-ratio: 5 / 6;
    overflow: hidden;
    border-radius: 12px;
}

.jobs-hero-slot img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.jobs-hero-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 12px;
}

.jobs-headline {
    font-family: 'Oswald', sans-serif;
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 700;
    color: #ffffff;
    text-transform: uppercase;
    text-align: center;
    letter-spacing: -0.01em;
    margin: 2.5rem 0;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

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

.no-jobs {
    text-align: center;
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    padding: 3rem 0;
}

/* ── Responsive ── */
@media (max-width: 900px) {
    .jobs-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .hero {
        grid-template-columns: 1fr;
    }

    .hero-image {
        height: 260px;
    }

    .hero-content {
        padding: 2.5rem 1.5rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .jobs-hero-images {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1.5rem 1.5rem 0;
    }

    .jobs-grid {
        grid-template-columns: 1fr;
        padding: 0 1.25rem;
    }
}
</style>
