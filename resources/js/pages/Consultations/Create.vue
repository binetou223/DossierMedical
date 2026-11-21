<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import { Plus, Trash2, Stethoscope, ArrowLeft, Pill, FileText } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    dossier: {
        id: number;
        patient: {
            id: number;
            numero_dossier: string;
            genre: string;
            groupe_sanguin: string | null;
            user: { name: string };
        };
        constantes?: Array<{
            tension_systolique: number | null;
            tension_diastolique: number | null;
            poids_kg: number | null;
            temperature_celsius: number | null;
        }>;
    };
    medecin: {
        id: number;
        titre: string;
        specialite: string;
        user: { name: string };
    };
    rendezVous?: {
        id: number;
        motif: string;
    } | null;
}>();

const derniereConstante = props.dossier.constantes?.[0];

const form = useForm({
    dossier_medical_id: props.dossier.id,
    medecin_id: props.medecin.id,
    rendez_vous_id: props.rendezVous?.id || null,
    motif_consultation: props.rendezVous?.motif || '',
    histoire_maladie: '',
    examen_physique: '',
    diagnostic: '',
    conduite_a_tenir: '',
    ordonnance: {
        instructions_generales: 'Respecter strictement les doses et la durée du traitement.',
        prescriptions: [
            {
                nom_medicament: '',
                dosage: '',
                forme: 'Comprimé',
                posologie: '1 comprimé matin et soir',
                duree: '7 jours',
                instructions: 'Pendant les repas',
            },
        ],
    },
});

function addPrescription() {
    form.ordonnance.prescriptions.push({
        nom_medicament: '',
        dosage: '',
        forme: 'Comprimé',
        posologie: '1 prise par jour',
        duree: '5 jours',
        instructions: '',
    });
}

function removePrescription(index: number) {
    if (form.ordonnance.prescriptions.length > 1) {
        form.ordonnance.prescriptions.splice(index, 1);
    }
}

function submit() {
    form.post('/consultations');
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Dossier Médical', href: `/dossiers/${dossier.id}` }, { title: 'Nouvelle Consultation', href: '#' }]">
        <Head title="Saisie de Consultation Médicale" />

        <div class="max-w-4xl mx-auto p-6 space-y-6">
            <!-- Rappel Patient & Contexte -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="`/dossiers/${dossier.id}`"><ArrowLeft class="h-4 w-4" /></Link>
                    </Button>
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight">Consultation Médicale</h1>
                        <p class="text-sm text-muted-foreground">
                            Patient : <span class="font-medium text-foreground">{{ dossier.patient.user.name }}</span> ({{ dossier.patient.numero_dossier }})
                        </p>
                    </div>
                </div>

                <!-- Rappel constantes -->
                <div v-if="derniereConstante" class="hidden sm:flex items-center gap-3 text-xs bg-muted/50 p-2.5 rounded-lg border">
                    <span v-if="derniereConstante.tension_systolique && derniereConstante.tension_diastolique">
                        TA: <strong>{{ derniereConstante.tension_systolique }}/{{ derniereConstante.tension_diastolique }} mmHg</strong>
                    </span>
                    <span v-if="derniereConstante.poids_kg">Poids: <strong>{{ derniereConstante.poids_kg }} kg</strong></span>
                    <span v-if="derniereConstante.temperature_celsius">Temp: <strong>{{ derniereConstante.temperature_celsius }} °C</strong></span>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Examen Clinique & Diagnostic -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center gap-2">
                            <Stethoscope class="h-5 w-5 text-primary" />
                            Observation Clinique & Diagnostic
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Label for="motif">Motif de Consultation *</Label>
                            <Input id="motif" v-model="form.motif_consultation" required placeholder="ex: Fièvre persistante depuis 3 jours, céphalées" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="histoire">Histoire de la Maladie</Label>
                                <textarea
                                    id="histoire"
                                    v-model="form.histoire_maladie"
                                    rows="3"
                                    class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                    placeholder="Circonstances d'apparition, évolution des symptômes..."
                                ></textarea>
                            </div>

                            <div class="space-y-2">
                                <Label for="examen">Examen Physique / Constatations</Label>
                                <textarea
                                    id="examen"
                                    v-model="form.examen_physique"
                                    rows="3"
                                    class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                    placeholder="Auscultation pulmonaire, palpation abdominale, pharyngite..."
                                ></textarea>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="diagnostic" class="font-semibold text-primary">Diagnostic Médical *</Label>
                            <textarea
                                id="diagnostic"
                                v-model="form.diagnostic"
                                required
                                rows="2"
                                class="w-full rounded-md border border-primary/30 p-2 text-sm bg-primary/5 focus:border-primary"
                                placeholder="ex: Rhinopharyngite aiguë d'origine virale sans signe de surinfection..."
                            ></textarea>
                        </div>

                        <div class="space-y-2">
                            <Label for="conduite">Conduite à Tenir / Conseils Hygiéno-Diététiques</Label>
                            <textarea
                                id="conduite"
                                v-model="form.conduite_a_tenir"
                                rows="2"
                                class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                placeholder="Repos strict, bonne hydratation, reconsulter si fièvre > 39°C après 48h..."
                            ></textarea>
                        </div>
                    </CardContent>
                </Card>

                <!-- Prescription Médicamenteuse & Ordonnance -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle class="text-lg flex items-center gap-2">
                                <Pill class="h-5 w-5 text-emerald-600" />
                                Prescription & Ordonnance Sécurisée
                            </CardTitle>
                            <CardDescription>Génération des lignes de prescription médicale.</CardDescription>
                        </div>
                        <Button type="button" size="sm" variant="outline" @click="addPrescription">
                            <Plus class="mr-1 h-3.5 w-3.5" /> Ajouter un médicament
                        </Button>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-for="(item, index) in form.ordonnance.prescriptions" :key="index" class="p-3 border rounded-lg bg-muted/20 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase text-muted-foreground">Médicament #{{ index + 1 }}</span>
                                <Button
                                    v-if="form.ordonnance.prescriptions.length > 1"
                                    type="button"
                                    size="icon"
                                    variant="ghost"
                                    class="h-6 w-6 text-destructive hover:bg-destructive/10"
                                    @click="removePrescription(index)"
                                >
                                    <Trash2 class="h-3.5 w-3.5" />
                                </Button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <Label class="text-xs">Nom du médicament *</Label>
                                    <Input v-model="item.nom_medicament" required placeholder="ex: Paracétamol" />
                                </div>
                                <div>
                                    <Label class="text-xs">Dosage</Label>
                                    <Input v-model="item.dosage" placeholder="ex: 1000 mg" />
                                </div>
                                <div>
                                    <Label class="text-xs">Forme</Label>
                                    <Input v-model="item.forme" placeholder="ex: Gélule, Sirop, Sachet" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <Label class="text-xs">Posologie *</Label>
                                    <Input v-model="item.posologie" required placeholder="ex: 1 comprimé 3 fois par jour" />
                                </div>
                                <div>
                                    <Label class="text-xs">Durée du traitement *</Label>
                                    <Input v-model="item.duree" required placeholder="ex: 5 jours" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2">
                            <Label for="instructions_generales" class="text-xs">Instructions générales de l'ordonnance</Label>
                            <Input id="instructions_generales" v-model="form.ordonnance.instructions_generales" />
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-3">
                    <Button type="button" variant="outline" as-child>
                        <Link :href="`/dossiers/${dossier.id}`">Annuler</Link>
                    </Button>
                    <Button type="submit" class="bg-primary text-primary-foreground" :disabled="form.processing">
                        {{ form.processing ? 'Enregistrement...' : 'Enregistrer la Consultation' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
