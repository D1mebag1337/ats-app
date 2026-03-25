<template>
    <div class="page">
        <PublicNavbar />

        <!-- Title pill / stelle selector -->
        <div class="title-bar">
            <span class="title-prefix">Bewerbung für:</span>
            <select v-model="selectedStellenID" class="stelle-select" required>
                <option :value="null" disabled>— Stelle auswählen —</option>
                <option v-for="s in stellen" :key="s.StellenID" :value="s.StellenID">
                    {{ s.Name }}
                </option>
            </select>
        </div>

        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="form-body">

                <!-- LEFT: Kontaktinformationen -->
                <div class="card">
                    <h2 class="card-title">Kontaktinformationen</h2>

                    <div class="field">
                        <label>Stelle</label>
                        <input type="text" :value="selectedStelleName" disabled />
                    </div>

                    <div class="field">
                        <label>Name</label>
                        <input type="text" :value="bewerber.Name" disabled />
                    </div>

                    <div class="field">
                        <label>Vorname</label>
                        <input type="text" :value="bewerber.Vorname" disabled />
                    </div>

                    <div class="field">
                        <label>Geburtsdatum</label>
                        <div class="date-row">
                            <input type="text" :value="gebTag"   disabled class="date-part" placeholder="TT" />
                            <input type="text" :value="gebMonat" disabled class="date-part" placeholder="MM" />
                            <input type="text" :value="gebJahr"  disabled class="date-year" placeholder="JJJJ" />
                        </div>
                    </div>

                    <div class="field">
                        <label>Strasse, Hausnummer</label>
                        <input type="text" :value="`${bewerber.Strasse}, ${bewerber.Hausnr}`" disabled />
                    </div>

                    <div class="field">
                        <label>PLZ</label>
                        <input type="text" :value="bewerber.Plz" disabled />
                    </div>

                    <div class="field">
                        <label>Ort</label>
                        <input type="text" :value="bewerber.Ort" disabled />
                    </div>
                </div>

                <!-- RIGHT: Dokumente + DSGVO -->
                <div class="card">

                    <div class="upload-field" v-for="doc in docs" :key="doc.key">
                        <span class="upload-label">{{ doc.label }}</span>
                        <label class="upload-btn" :for="doc.key">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 20h14v-2H5v2zm0-10h4v6h6v-6h4l-7-7-7 7z"/>
                            </svg>
                        </label>
                        <input
                            :id="doc.key"
                            type="file"
                            accept=".pdf"
                            class="file-input"
                            @change="onFile(doc.key, $event)"
                        />
                        <span v-if="files[doc.key]" class="file-name">{{ files[doc.key].name }}</span>
                        <span v-if="errors[doc.key]" class="field-error">{{ errors[doc.key] }}</span>
                    </div>

                    <div class="dsgvo-field">
                        <input id="dsgvo" type="checkbox" v-model="dsgvo" />
                        <label for="dsgvo" class="dsgvo-text">
                            „Hiermit willige ich ein, dass [Unternehmen] meine Daten zum Zweck der Durchführung des Bewerbungsverfahrens verarbeitet. Ich kann diese Einwilligung jederzeit widerrufen. Die Datenschutzerklärung habe ich zur Kenntnis genommen."
                        </label>
                    </div>
                    <span v-if="errors.Datenschutzerklaerung" class="field-error">{{ errors.Datenschutzerklaerung }}</span>

                </div>

            </div>

            <!-- Submit -->
            <div class="submit-row">
                <button type="submit" class="submit-btn" :disabled="processing">
                    {{ processing ? 'Wird gesendet…' : 'Bewerbung absenden' }}
                </button>
            </div>
        </form>

    </div>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PublicNavbar from '@/components/PublicNavbar.vue'

const props = defineProps({
    stellen:  { type: Array,  default: () => [] },
    bewerber: { type: Object, required: true },
})

const docs = [
    { key: 'Anschreiben', label: 'Anschreiben' },
    { key: 'Lebenslauf',  label: 'Lebenslauf' },
    { key: 'Zeugnisse',   label: 'Zeugnisse' },
    { key: 'Zertifikate', label: 'Zertifikate' },
]

const selectedStellenID = ref(null)
const selectedStelleName = computed(
    () => props.stellen.find(s => s.StellenID === selectedStellenID.value)?.Name ?? ''
)

const files      = reactive({})
const dsgvo      = ref(false)
const errors     = reactive({})
const processing = ref(false)

// parse Gebdatum "YYYY-MM-DD"
const gebTag   = computed(() => props.bewerber.Gebdatum?.split('-')[2] ?? '')
const gebMonat = computed(() => props.bewerber.Gebdatum?.split('-')[1] ?? '')
const gebJahr  = computed(() => props.bewerber.Gebdatum?.split('-')[0] ?? '')

function onFile(key, event) {
    files[key] = event.target.files[0] ?? null
}

function submit() {
    processing.value = true
    Object.keys(errors).forEach(k => delete errors[k])

    const data = new FormData()
    data.append('StellenID', selectedStellenID.value)
    data.append('Datenschutzerklaerung', dsgvo.value ? '1' : '0')
    docs.forEach(d => {
        if (files[d.key]) data.append(d.key, files[d.key])
    })

    router.post('/bewerbungen', data, {
        forceFormData: true,
        onError:  (e) => Object.assign(errors, e),
        onFinish: () => { processing.value = false },
    })
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Serif+4:ital,wght@0,300;0,400;0,600;1,300&display=swap');

.page {
    min-height: 100vh;
    background: radial-gradient(ellipse at top, #3a8a3a 0%, #1f5c1f 60%, #0f3a0f 100%);
    font-family: 'Source Serif 4', Georgia, serif;
    padding-bottom: 5rem;
}

/* ── Title pill ── */
.title-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.6rem;
    padding: 1.75rem 1rem 0;
}

.title-prefix {
    background: #e8e8e8;
    border-radius: 999px 0 0 999px;
    padding: 0.55rem 1.25rem 0.55rem 1.75rem;
    font-size: 1rem;
    color: #1a1a1a;
    white-space: nowrap;
}

.stelle-select {
    background: #e8e8e8;
    border: none;
    border-radius: 0 999px 999px 0;
    padding: 0.55rem 1.75rem 0.55rem 0.5rem;
    font-family: 'Source Serif 4', serif;
    font-size: 1rem;
    font-style: italic;
    font-weight: 700;
    color: #1a1a1a;
    cursor: pointer;
    outline: none;
    appearance: auto;
}

/* ── Two-column layout ── */
.form-body {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    max-width: 860px;
    margin: 1.75rem auto 0;
    padding: 0 1.5rem;
}

/* ── Cards ── */
.card {
    background: #e8e8e8;
    border-radius: 20px;
    padding: 2rem 1.75rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 700;
    text-align: center;
    color: #1a1a1a;
    margin: 0 0 1.5rem;
}

/* ── Contact fields ── */
.field {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    margin-bottom: 1rem;
}

.field label {
    font-size: 0.78rem;
    color: #444;
}

.field input,
.date-part,
.date-year {
    background: #ffffff;
    border: 1px solid #c8c8c8;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-family: 'Source Serif 4', serif;
    font-size: 0.88rem;
    color: #1a1a1a;
    width: 100%;
    box-sizing: border-box;
}

.field input:disabled,
.date-part:disabled,
.date-year:disabled {
    background: #f0f0f0;
    color: #555;
    cursor: default;
}

.date-row {
    display: flex;
    gap: 0.5rem;
}

.date-part { width: 64px; flex-shrink: 0; }
.date-year { flex: 1; }

/* ── Upload fields ── */
.upload-field {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    margin-bottom: 1.1rem;
}

.upload-label {
    font-size: 0.88rem;
    color: #1a1a1a;
    width: 90px;
    flex-shrink: 0;
}

.upload-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #d8d8d8;
    border-radius: 10px;
    cursor: pointer;
    flex-shrink: 0;
    color: #333;
    transition: background 0.15s;
}

.upload-btn:hover { background: #c8c8c8; }

.file-input { display: none; }

.file-name {
    font-size: 0.75rem;
    color: #2d7a2d;
    flex: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* ── DSGVO ── */
.dsgvo-field {
    display: flex;
    gap: 0.75rem;
    align-items: flex-start;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid #d0d0d0;
}

.dsgvo-field input[type="checkbox"] {
    margin-top: 0.25rem;
    flex-shrink: 0;
    width: 16px;
    height: 16px;
    accent-color: #2d7a2d;
    cursor: pointer;
}

.dsgvo-text {
    font-size: 0.76rem;
    color: #444;
    line-height: 1.5;
    cursor: pointer;
}

.field-error {
    font-size: 0.75rem;
    color: #c0392b;
    margin-top: 0.25rem;
}

/* ── Submit ── */
.submit-row {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.submit-btn {
    background: #e8e8e8;
    color: #1a1a1a;
    border: none;
    border-radius: 999px;
    padding: 0.85rem 3rem;
    font-family: 'Oswald', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: opacity 0.15s;
}

.submit-btn:hover:not(:disabled) { opacity: 0.85; }
.submit-btn:disabled { opacity: 0.55; cursor: not-allowed; }

/* ── Responsive ── */
@media (max-width: 680px) {
    .form-body {
        grid-template-columns: 1fr;
    }
}
</style>
