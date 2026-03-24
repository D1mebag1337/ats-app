<template>
    <div class="dashboard-page">

        <PublicNavbar />

        <div class="dashboard-body">
            <div class="dashboard-card">
                <h2 class="card-title">Übersicht Bewerbungen</h2>

                <table v-if="bewerbungen.length > 0" class="bew-table">
                    <thead>
                        <tr>
                            <th>Stelle</th>
                            <th>Status</th>
                            <th v-if="isRecruiter">Bewerber</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="bew in bewerbungen" :key="bew.BewerbungID">
                            <td class="col-stelle">{{ bew.stelle?.Name ?? '—' }}</td>
                            <td class="col-status">{{ bew.Status ?? '—' }}</td>
                            <td v-if="isRecruiter" class="col-bewerber">
                                {{ bew.bewerber ? `${bew.bewerber.Vorname} ${bew.bewerber.Name}` : '—' }}
                            </td>
                            <td class="col-action">
                                <Link
                                    :href="`/bewerbungen/${bew.BewerbungID}`"
                                    class="action-btn"
                                >
                                    Unterlagen aufrufen
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p v-else class="empty-state">Noch keine Bewerbungen vorhanden.</p>
            </div>
        </div>

    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import PublicNavbar from '@/components/PublicNavbar.vue'

defineProps({
    bewerbungen: {
        type: Array,
        default: () => [],
    },
    isRecruiter: {
        type: Boolean,
        default: false,
    },
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');

.dashboard-page {
    min-height: 100vh;
    background: radial-gradient(ellipse at top, #3a8a3a 0%, #1f5c1f 60%, #0f3a0f 100%);
    font-family: 'Source Serif 4', Georgia, serif;
}

/* ── Body ── */
.dashboard-body {
    display: flex;
    justify-content: center;
    padding: 3rem 1.5rem 5rem;
}

/* ── Card ── */
.dashboard-card {
    background: #e4e4e4;
    border-radius: 20px;
    padding: 2.5rem 3rem;
    width: 100%;
    max-width: 820px;
}

.card-title {
    font-family: 'Source Serif 4', serif;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
    text-decoration: underline;
    text-underline-offset: 5px;
    color: #1a1a1a;
    margin: 0 0 2rem;
}

/* ── Table ── */
.bew-table {
    width: 100%;
    border-collapse: collapse;
}

.bew-table thead tr {
    border-bottom: 1.5px solid #c0c0c0;
}

.bew-table th {
    font-family: 'Source Serif 4', serif;
    font-size: 0.92rem;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    padding: 0.5rem 1rem 0.75rem;
    text-align: center;
}

.bew-table td {
    padding: 1.1rem 1rem;
    text-align: center;
    font-size: 0.92rem;
    color: #1a1a1a;
    border-bottom: 1px solid #d0d0d0;
}

.bew-table tbody tr:last-child td {
    border-bottom: none;
}

.col-stelle {
    font-weight: 400;
}

.col-status {
    color: #2a2a2a;
}

/* ── Action button ── */
.action-btn {
    display: inline-block;
    background: #1a1a1a;
    color: #ffffff;
    font-family: 'Source Serif 4', serif;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.45rem 1rem;
    border-radius: 999px;
    text-decoration: none;
    white-space: nowrap;
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.action-btn:hover {
    opacity: 0.82;
    transform: translateY(-1px);
}

/* ── Empty state ── */
.empty-state {
    text-align: center;
    color: #666;
    font-size: 0.95rem;
    padding: 2rem 0;
}

/* ── Responsive ── */
@media (max-width: 600px) {
    .dashboard-card {
        padding: 2rem 1.25rem;
    }

    .bew-table th,
    .bew-table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.82rem;
    }
}
</style>
