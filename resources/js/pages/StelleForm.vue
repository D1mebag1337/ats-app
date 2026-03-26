<template>
    <div class="page">
        <PublicNavbar />

        <div class="page-body">
            <div class="form-card">
                <h1 class="form-title">Stelle erstellen/<br>bearbeiten</h1>

                <form @submit.prevent="submit" enctype="multipart/form-data">

                    <!-- Stellenname -->
                    <div class="field">
                        <label>Stellenname</label>
                        <input v-model="form.Name" type="text" placeholder="z. B. Junior Project Consultant" required />
                        <span v-if="errors.Name" class="field-error">{{ errors.Name }}</span>
                    </div>

                    <!-- Kurzbeschreibung -->
                    <div class="field">
                        <label>Kurzbeschreibung (max. 30 Wörter)</label>
                        <textarea v-model="form.Kurzbeschreibung" rows="4" placeholder="Lorem ipsum…" required />
                        <span v-if="kurzbeschreibungWordCount > 30" class="field-warning">
                            {{ kurzbeschreibungWordCount }} / 30 Wörter — bitte kürzen.
                        </span>
                        <span v-if="errors.Kurzbeschreibung" class="field-error">{{ errors.Kurzbeschreibung }}</span>
                    </div>

                    <!-- Beschreibung -->
                    <div class="field">
                        <label>Allgemeine Stellenbeschreibung (max. 60 Wörter)</label>
                        <textarea v-model="form.Beschreibung" rows="5" placeholder="Ausführliche Beschreibung der Stelle…" required />
                        <span v-if="beschreibungWordCount > 60" class="field-warning">
                            {{ beschreibungWordCount }} / 60 Wörter — bitte kürzen.
                        </span>
                        <span v-if="errors.Beschreibung" class="field-error">{{ errors.Beschreibung }}</span>
                    </div>

                    <!-- Arbeitsorte -->
                    <div class="field">
                        <label>Arbeitsorte</label>
                        <input v-model="form.Arbeitsorte" type="text" placeholder="z. B. Berlin, Hamburg, Remote" required />
                        <span v-if="errors.Arbeitsorte" class="field-error">{{ errors.Arbeitsorte }}</span>
                    </div>

                    <!-- Aufgaben -->
                    <div class="field">
                        <label>Aufgaben (max. 5 Anstriche)</label>
                        <div class="bullet-list">
                            <div
                                v-for="(item, i) in form.Aufgaben"
                                :key="i"
                                class="bullet-row"
                            >
                                <span class="bullet-dot">•</span>
                                <input
                                    v-model="form.Aufgaben[i]"
                                    type="text"
                                    placeholder="Aufgabe beschreiben…"
                                />
                                <button type="button" class="bullet-remove" @click="removeItem('Aufgaben', i)">✕</button>
                            </div>
                            <button
                                v-if="form.Aufgaben.length < 5"
                                type="button"
                                class="bullet-add"
                                @click="addItem('Aufgaben')"
                            >+ Anstrich hinzufügen</button>
                        </div>
                        <span v-if="errors.Aufgaben" class="field-error">{{ errors.Aufgaben }}</span>
                    </div>

                    <!-- Voraussetzungen -->
                    <div class="field">
                        <label>Voraussetzungen (max. 5 Anstriche)</label>
                        <div class="bullet-list">
                            <div
                                v-for="(item, i) in form.Voraussetzungen"
                                :key="i"
                                class="bullet-row"
                            >
                                <span class="bullet-dot">•</span>
                                <input
                                    v-model="form.Voraussetzungen[i]"
                                    type="text"
                                    placeholder="Voraussetzung beschreiben…"
                                />
                                <button type="button" class="bullet-remove" @click="removeItem('Voraussetzungen', i)">✕</button>
                            </div>
                            <button
                                v-if="form.Voraussetzungen.length < 5"
                                type="button"
                                class="bullet-add"
                                @click="addItem('Voraussetzungen')"
                            >+ Anstrich hinzufügen</button>
                        </div>
                        <span v-if="errors.Voraussetzungen" class="field-error">{{ errors.Voraussetzungen }}</span>
                    </div>

                    <!-- Bild der Stellenanzeige -->
                    <div class="field">
                        <label>Bild der Stellenanzeige</label>
                        <select v-model="form.ImageID">
                            <option :value="null">— kein Bild —</option>
                            <option v-for="img in images" :key="img.ImageID" :value="img.ImageID">
                                {{ img.Alternativtext }}
                            </option>
                        </select>
                    </div>

                    <!-- Online-Status -->
                    <div class="field status-field">
                        <div class="status-info">
                            <span>Stellenanzeige aktivieren/deaktivieren</span>
                            <span>Aktueller Status: <em>{{ form.Online ? 'Aktiv' : 'Inaktiv' }}</em></span>
                        </div>
                        <button type="button" class="toggle-btn" @click="form.Online = !form.Online">
                            {{ form.Online ? 'Deaktivieren' : 'Aktivieren' }}
                        </button>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="submit-btn" :disabled="processing">
                        {{ processing ? 'Wird gespeichert…' : (stelle ? 'Speichern' : 'Stelle erstellen') }}
                    </button>

                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PublicNavbar from '@/components/PublicNavbar.vue'

const props = defineProps({
    stelle: { type: Object, default: null },
    images: { type: Array, default: () => [] },
})

const form = reactive({
    Name:             props.stelle?.Name             ?? '',
    Kurzbeschreibung: props.stelle?.Kurzbeschreibung ?? '',
    Beschreibung:     props.stelle?.Beschreibung     ?? '',
    Arbeitsorte:      props.stelle?.Arbeitsorte      ?? '',
    Aufgaben:        props.stelle?.Aufgaben        ?? [],
    Voraussetzungen: props.stelle?.Voraussetzungen ?? [],
    ImageID:         props.stelle?.ImageID         ?? null,
    Online:          props.stelle?.Online          ?? false,
})

const kurzbeschreibungWordCount = computed(() =>
    form.Kurzbeschreibung.trim() === '' ? 0 : form.Kurzbeschreibung.trim().split(/\s+/).length
)

const beschreibungWordCount = computed(() =>
    form.Beschreibung.trim() === '' ? 0 : form.Beschreibung.trim().split(/\s+/).length
)

const errors = reactive({})
const processing = ref(false)

function addItem(field) {
    if (form[field].length < 5) form[field].push('')
}

function removeItem(field, index) {
    form[field].splice(index, 1)
}

function submit() {
    processing.value = true
    Object.keys(errors).forEach(k => delete errors[k])

    const payload = {
        Name:             form.Name,
        Kurzbeschreibung: form.Kurzbeschreibung,
        Beschreibung:     form.Beschreibung,
        Arbeitsorte:      form.Arbeitsorte,
        Aufgaben:         form.Aufgaben.filter(a => a.trim()),
        Voraussetzungen:  form.Voraussetzungen.filter(v => v.trim()),
        Online:           form.Online,
        ImageID:          form.ImageID ?? null,
    }

    const callbacks = {
        onError:  (e) => Object.assign(errors, e),
        onFinish: () => { processing.value = false },
    }

    if (props.stelle) {
        router.put(`/stellen/${props.stelle.StellenID}`, payload, callbacks)
    } else {
        router.post('/stellen', payload, callbacks)
    }
}
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
    padding: 2.5rem 1.25rem 5rem;
}

/* ── Card ── */
.form-card {
    background: #e8e8e8;
    border-radius: 20px;
    padding: 2.5rem 2.25rem;
    width: 100%;
    max-width: 440px;
}

.form-title {
    font-family: 'Source Serif 4', serif;
    font-size: 1.2rem;
    font-weight: 700;
    font-style: italic;
    text-align: center;
    color: #1a1a1a;
    margin: 0 0 2rem;
    line-height: 1.4;
}

/* ── Fields ── */
.field {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    margin-bottom: 1.25rem;
}

.field label {
    font-size: 0.8rem;
    color: #333;
}

.field input,
.field textarea,
.field select {
    background: #ffffff;
    border: 1px solid #c8c8c8;
    border-radius: 8px;
    padding: 0.55rem 0.85rem;
    font-family: 'Source Serif 4', serif;
    font-size: 0.9rem;
    color: #1a1a1a;
    outline: none;
    width: 100%;
    box-sizing: border-box;
    transition: border-color 0.15s;
}

.field input:focus,
.field textarea:focus,
.field select:focus {
    border-color: #2d7a2d;
}

.field textarea {
    resize: vertical;
}

.field-error {
    font-size: 0.78rem;
    color: #c0392b;
}

.field-warning {
    font-size: 0.78rem;
    color: #e67e22;
}

/* ── Bullet list ── */
.bullet-list {
    background: #ffffff;
    border: 1px solid #c8c8c8;
    border-radius: 8px;
    padding: 0.6rem 0.85rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.bullet-row {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.bullet-dot {
    color: #333;
    flex-shrink: 0;
}

.bullet-row input {
    border: none;
    padding: 0.2rem 0;
    font-size: 0.88rem;
    flex: 1;
    background: transparent;
}

.bullet-row input:focus {
    border-color: transparent;
    outline: none;
}

.bullet-remove {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 0.75rem;
    padding: 0 0.2rem;
    flex-shrink: 0;
}

.bullet-remove:hover { color: #c0392b; }

.bullet-add {
    background: none;
    border: none;
    color: #2d7a2d;
    font-size: 0.82rem;
    cursor: pointer;
    padding: 0.2rem 0;
    text-align: left;
}

.bullet-add:hover { text-decoration: underline; }

/* ── Status toggle ── */
.status-field {
    gap: 0.6rem;
}

.status-info {
    display: flex;
    flex-direction: column;
    font-size: 0.85rem;
    color: #333;
    gap: 0.15rem;
}

.status-info em {
    font-style: italic;
    font-weight: 600;
}

.toggle-btn {
    align-self: center;
    background: #1a1a1a;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 0.55rem 1.75rem;
    font-family: 'Source Serif 4', serif;
    font-size: 0.88rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s;
}

.toggle-btn:hover { opacity: 0.82; }

/* ── Submit ── */
.submit-btn {
    width: 100%;
    background: #2d7a2d;
    color: #fff;
    border: none;
    border-radius: 999px;
    padding: 0.75rem;
    font-family: 'Oswald', sans-serif;
    font-size: 0.95rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    margin-top: 0.5rem;
    transition: opacity 0.15s;
}

.submit-btn:hover:not(:disabled) { opacity: 0.87; }
.submit-btn:disabled { opacity: 0.55; cursor: not-allowed; }
</style>
