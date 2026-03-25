<template>
    <div class="page">
        <PublicNavbar />

        <div class="page-body">
            <div class="card">
                <h2 class="card-title">Stellenanzeigen</h2>

                <table v-if="stellen.length > 0" class="table">
                    <thead>
                        <tr>
                            <th>Stelle</th>
                            <th>Arbeitsort</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="stelle in stellen" :key="stelle.StellenID">
                            <td class="col-name">{{ stelle.Name }}</td>
                            <td>{{ stelle.Arbeitsorte }}</td>
                            <td>
                                <span :class="stelle.Online ? 'badge-active' : 'badge-inactive'">
                                    {{ stelle.Online ? 'Aktiv' : 'Inaktiv' }}
                                </span>
                            </td>
                            <td class="col-action">
                                <Link :href="`/stellen/${stelle.StellenID}/edit`" class="action-btn">
                                    Bearbeiten
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <p v-else class="empty-state">Noch keine Stellen vorhanden.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import PublicNavbar from '@/components/PublicNavbar.vue'

defineProps({
    stellen: { type: Array, default: () => [] },
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');

.page {
    min-height: 100vh;
    background: radial-gradient(ellipse at top, #3a8a3a 0%, #1f5c1f 60%, #0f3a0f 100%);
    font-family: 'Source Serif 4', Georgia, serif;
}

.page-body {
    display: flex;
    justify-content: center;
    padding: 3rem 1.5rem 5rem;
}

.card {
    background: #e4e4e4;
    border-radius: 20px;
    padding: 2.5rem 3rem;
    width: 100%;
    max-width: 720px;
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

.table {
    width: 100%;
    border-collapse: collapse;
}

.table thead tr {
    border-bottom: 1.5px solid #c0c0c0;
}

.table th {
    font-family: 'Source Serif 4', serif;
    font-size: 0.92rem;
    font-weight: 700;
    font-style: italic;
    color: #1a1a1a;
    padding: 0.5rem 1rem 0.75rem;
    text-align: center;
}

.table td {
    padding: 1.1rem 1rem;
    text-align: center;
    font-size: 0.92rem;
    color: #1a1a1a;
    border-bottom: 1px solid #d0d0d0;
}

.table tbody tr:last-child td {
    border-bottom: none;
}

.col-name { font-weight: 400; text-align: left; }
.col-action { text-align: right; }

.badge-active,
.badge-inactive {
    display: inline-block;
    padding: 0.2rem 0.75rem;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
}

.badge-active   { background: #d4edda; color: #1a6b2a; }
.badge-inactive { background: #f0f0f0; color: #777; }

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

.empty-state {
    text-align: center;
    color: #666;
    font-size: 0.95rem;
    padding: 2rem 0;
}
</style>
