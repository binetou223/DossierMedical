<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import {
    Activity,
    AlertTriangle,
    FileText,
    Heart,
    Plus,
    Stethoscope,
    Upload,
    Calendar,
    Phone,
    User,
    CheckCircle,
    Download
} from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    dossier: {
        id: number;
        patient_id: number;
        antecedents_personnels: string | null;
        antecedents_chirurgicaux: string | null;
        antecedents_familiaux: string | null;
        allergies: string | null;
        traitements_chroniques: string | null;
        regime_alimentaire: string | null;
        notes_confidentielles: string | null;
        patient: {
            id: number;
            numero_dossier: string;
            date_naissance: string | null;
            genre: string;
            groupe_sanguin: string | null;
            user: {
                id: number;
                name: string;
                email: string;
                telephone: string | null;
            };
        };
        constantes: Array<{
            id: number;
            poids_kg: number | null;
            taille_cm: number | null;
            tension_systolique: number | null;
            tension_diastolique: number | null;
            frequence_cardiaque: number | null;
            temperature_celsius: number | null;
            glycemie_g_l: number | null;
            imc: number | null;
            observations: string | null;
            date_mesure: string;
        }>;
        documents: Array<{
            id: number;
            nom: string;
            type_document: string;
            chemin_fichier: string;
            mime_type: string;
            created_at: string;
        }>;
        consultations: Array<{
            id: number;
            date_heure: string;
            motif_consultation: string;
            diagnostic: string;
            est_cloturee: boolean;
            medecin: {
                titre: string;
                user: { name: string };
            };
            ordonnance?: {
                id: number;
                numero_reference: string;
                prescriptions: Array<{
                    id: number;
                    nom_medicament: string;
                    posologie: string;
                    duree: string;
                }>;
            };
        }>;
    };
    alertes?: Array<{
        type: string;
        niveau: string;
        message: string;
        valeur: any;
    }>;
}>();

const page = usePage();
const currentUser = page.props.auth.user;
const activeTab = ref<'constantes' | 'antecedents' | 'consultations' | 'documents'>('constantes');

// Formulaire Constantes
const constanteForm = useForm({
    dossier_medical_id: props.dossier.id,
    poids_kg: '',
    taille_cm: '',
    tension_systolique: '',
    tension_diastolique: '',
    frequence_cardiaque: '',
    temperature_celsius: '37.0',
    glycemie_g_l: '',
    observations: '',
});

function submitConstante() {
    constanteForm.post('/dossiers/constantes', {
        preserveScroll: true,
        onSuccess: () => constanteForm.reset('poids_kg', 'taille_cm', 'tension_systolique', 'tension_diastolique', 'frequence_cardiaque', 'glycemie_g_l', 'observations'),
    });
}

// Formulaire Antécédents
const antecedentsForm = useForm({
    antecedents_personnels: props.dossier.antecedents_personnels || '',
    antecedents_chirurgicaux: props.dossier.antecedents_chirurgicaux || '',
    antecedents_familiaux: props.dossier.antecedents_familiaux || '',
    allergies: props.dossier.allergies || '',
    traitements_chroniques: props.dossier.traitements_chroniques || '',
    regime_alimentaire: props.dossier.regime_alimentaire || '',
    notes_confidentielles: props.dossier.notes_confidentielles || '',
});

function submitAntecedents() {
    antecedentsForm.put(`/dossiers/${props.dossier.id}`, {
        preserveScroll: true,
    });
}

// Formulaire Documents
const documentForm = useForm({
    dossier_medical_id: props.dossier.id,
    nom: '',
    type_document: 'Bilan biologique',
    fichier: null as File | null,
    description: '',
});

function submitDocument() {
    documentForm.post('/dossiers/documents', {
        preserveScroll: true,
        onSuccess: () => documentForm.reset('nom', 'fichier', 'description'),
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Dossier Médical', href: '#' }, { title: dossier.patient.user.name, href: '#' }]">
        <Head :title="`Dossier Médical - ${dossier.patient.user.name}`" />

        <div class="space-y-6 p-6">
            <!-- Entête Patient & Dossier -->
            <div class="bg-card border border-border rounded-xl p-6 shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold tracking-tight text-foreground">{{ dossier.patient.user.name }}</h1>
                            <Badge variant="outline" class="font-mono text-sm bg-primary/5 text-primary border-primary/20">
                                {{ dossier.patient.numero_dossier }}
                            </Badge>
                            <Badge v-if="dossier.patient.groupe_sanguin" variant="secondary" class="font-bold">
                                {{ dossier.patient.groupe_sanguin }}
                            </Badge>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs text-muted-foreground pt-1">
                            <span>Genre : {{ dossier.patient.genre === 'F' ? 'Féminin' : 'Masculin' }}</span>
                            <span>•</span>
                            <span>Date de naissance : {{ dossier.patient.date_naissance || 'Non renseignée' }}</span>
                            <span>•</span>
                            <span>Tél : {{ dossier.patient.user.telephone || 'Non renseigné' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <Button
                            v-if="currentUser.role === 'medecin'"
                            as-child
                            class="bg-blue-600 hover:bg-blue-700 text-white"
                        >
                            <Link :href="`/consultations/create?dossier_id=${dossier.id}`">
                                <Stethoscope class="mr-2 h-4 w-4" />
                                Nouvelle Consultation
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Bannières d'alertes cliniques issues du Pattern Strategy -->
            <div v-if="alertes && alertes.length > 0" class="space-y-2">
                <div
                    v-for="(alerte, idx) in alertes"
                    :key="idx"
                    :class="[
                        'p-4 rounded-lg border flex items-start gap-3',
                        alerte.niveau === 'danger'
                            ? 'bg-destructive/10 border-destructive/30 text-destructive'
                            : 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-950/30 dark:border-amber-800 dark:text-amber-300'
                    ]"
                >
                    <AlertTriangle class="h-5 w-5 shrink-0 mt-0.5" />
                    <div>
                        <div class="font-semibold text-sm">Alerte Clinique : {{ alerte.type }}</div>
                        <div class="text-xs mt-0.5">{{ alerte.message }}</div>
                    </div>
                </div>
            </div>

            <!-- Navigation par Onglets -->
            <div class="flex border-b border-border space-x-4">
                <button
                    @click="activeTab = 'constantes'"
                    :class="['pb-2 text-sm font-medium border-b-2 transition-colors', activeTab === 'constantes' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground']"
                >
                    Constantes Vitales ({{ dossier.constantes.length }})
                </button>
                <button
                    @click="activeTab = 'antecedents'"
                    :class="['pb-2 text-sm font-medium border-b-2 transition-colors', activeTab === 'antecedents' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground']"
                >
                    Antécédents & Allergies
                </button>
                <button
                    @click="activeTab = 'consultations'"
                    :class="['pb-2 text-sm font-medium border-b-2 transition-colors', activeTab === 'consultations' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground']"
                >
                    Consultations & Ordonnances ({{ dossier.consultations.length }})
                </button>
                <button
                    @click="activeTab = 'documents'"
                    :class="['pb-2 text-sm font-medium border-b-2 transition-colors', activeTab === 'documents' ? 'border-primary text-primary' : 'border-transparent text-muted-foreground hover:text-foreground']"
                >
                    Documents Médicaux ({{ dossier.documents.length }})
                </button>
            </div>

            <!-- ONGLET 1 : CONSTANTES VITALES -->
            <div v-if="activeTab === 'constantes'" class="space-y-6">
                <!-- Formulaire Prise de Constantes -->
                <Card v-if="currentUser.role === 'medecin' || currentUser.role === 'secretaire' || currentUser.role === 'admin'">
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center gap-2">
                            <Activity class="h-5 w-5 text-primary" />
                            Enregistrer une prise de constantes
                        </CardTitle>
                        <CardDescription>Saisie rapide lors de l'accueil ou de l'examen clinique.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submitConstante" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="space-y-1">
                                <Label for="poids_kg" class="text-xs">Poids (kg)</Label>
                                <Input id="poids_kg" type="number" step="0.1" v-model="constanteForm.poids_kg" placeholder="ex: 70" />
                            </div>
                            <div class="space-y-1">
                                <Label for="taille_cm" class="text-xs">Taille (cm)</Label>
                                <Input id="taille_cm" type="number" step="1" v-model="constanteForm.taille_cm" placeholder="ex: 175" />
                            </div>
                            <div class="space-y-1">
                                <Label for="tension_sys" class="text-xs">Tension Systolique (mmHg)</Label>
                                <Input id="tension_sys" type="number" v-model="constanteForm.tension_systolique" placeholder="ex: 120" />
                            </div>
                            <div class="space-y-1">
                                <Label for="tension_dia" class="text-xs">Tension Diastolique (mmHg)</Label>
                                <Input id="tension_dia" type="number" v-model="constanteForm.tension_diastolique" placeholder="ex: 80" />
                            </div>
                            <div class="space-y-1">
                                <Label for="frequence_cardiaque" class="text-xs">Pouls (bpm)</Label>
                                <Input id="frequence_cardiaque" type="number" v-model="constanteForm.frequence_cardiaque" placeholder="ex: 72" />
                            </div>
                            <div class="space-y-1">
                                <Label for="temperature" class="text-xs">Température (°C)</Label>
                                <Input id="temperature" type="number" step="0.1" v-model="constanteForm.temperature_celsius" placeholder="37.0" />
                            </div>
                            <div class="space-y-1">
                                <Label for="glycemie" class="text-xs">Glycémie à jeun (g/L)</Label>
                                <Input id="glycemie" type="number" step="0.01" v-model="constanteForm.glycemie_g_l" placeholder="ex: 0.95" />
                            </div>
                            <div class="flex items-end">
                                <Button type="submit" class="w-full" :disabled="constanteForm.processing">
                                    <Plus class="mr-1 h-4 w-4" /> Enregistrer
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <!-- Tableau Historique des Constantes -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Historique des mesures</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="border-b border-border bg-muted/50 text-xs uppercase font-semibold text-muted-foreground">
                                    <tr>
                                        <th class="p-3">Date</th>
                                        <th class="p-3">Tension (mmHg)</th>
                                        <th class="p-3">Poids / Taille</th>
                                        <th class="p-3">IMC (kg/m²)</th>
                                        <th class="p-3">Pouls (bpm)</th>
                                        <th class="p-3">Temp (°C)</th>
                                        <th class="p-3">Glycémie (g/L)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    <tr v-for="c in dossier.constantes" :key="c.id" class="hover:bg-muted/30">
                                        <td class="p-3 text-xs text-muted-foreground">
                                            {{ new Date(c.date_mesure).toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'short' }) }}
                                        </td>
                                        <td class="p-3 font-semibold">
                                            <span v-if="c.tension_systolique && c.tension_diastolique">
                                                {{ c.tension_systolique }}/{{ c.tension_diastolique }}
                                            </span>
                                            <span v-else class="text-muted-foreground text-xs">-</span>
                                        </td>
                                        <td class="p-3">
                                            {{ c.poids_kg ? `${c.poids_kg} kg` : '-' }} / {{ c.taille_cm ? `${c.taille_cm} cm` : '-' }}
                                        </td>
                                        <td class="p-3">
                                            <Badge v-if="c.imc" variant="outline" class="font-mono">{{ c.imc }}</Badge>
                                            <span v-else>-</span>
                                        </td>
                                        <td class="p-3">{{ c.frequence_cardiaque || '-' }}</td>
                                        <td class="p-3">{{ c.temperature_celsius ? `${c.temperature_celsius}°C` : '-' }}</td>
                                        <td class="p-3">{{ c.glycemie_g_l ? `${c.glycemie_g_l}` : '-' }}</td>
                                    </tr>
                                    <tr v-if="dossier.constantes.length === 0">
                                        <td colspan="7" class="p-6 text-center text-muted-foreground">
                                            Aucune constante vitale enregistrée pour ce dossier.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- ONGLET 2 : ANTÉCÉDENTS & ALLERGIES -->
            <div v-if="activeTab === 'antecedents'">
                <Card>
                    <CardHeader>
                        <CardTitle>Profil Médical & Facteurs de Risque</CardTitle>
                        <CardDescription>Antécédents personnels, familiaux, chirurgicaux et terrain allergique.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submitAntecedents" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="allergies" class="text-destructive font-semibold">Allergies Médicamenteuses & Alimentaires</Label>
                                    <textarea
                                        id="allergies"
                                        v-model="antecedentsForm.allergies"
                                        rows="3"
                                        placeholder="ex: Pénicilline, Arachides, Iode..."
                                        class="w-full rounded-md border border-input p-2 text-sm bg-destructive/5"
                                        :disabled="currentUser.role !== 'medecin' && currentUser.role !== 'admin'"
                                    ></textarea>
                                </div>

                                <div class="space-y-2">
                                    <Label for="traitements_chroniques">Traitements Chroniques en cours</Label>
                                    <textarea
                                        id="traitements_chroniques"
                                        v-model="antecedentsForm.traitements_chroniques"
                                        rows="3"
                                        placeholder="ex: Amlodipine 5mg, Metformine 850mg..."
                                        class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                        :disabled="currentUser.role !== 'medecin' && currentUser.role !== 'admin'"
                                    ></textarea>
                                </div>

                                <div class="space-y-2">
                                    <Label for="antecedents_personnels">Antécédents Personnels (Médicaux)</Label>
                                    <textarea
                                        id="antecedents_personnels"
                                        v-model="antecedentsForm.antecedents_personnels"
                                        rows="3"
                                        placeholder="ex: Diabète de type 2, Asthme..."
                                        class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                        :disabled="currentUser.role !== 'medecin' && currentUser.role !== 'admin'"
                                    ></textarea>
                                </div>

                                <div class="space-y-2">
                                    <Label for="antecedents_chirurgicaux">Antécédents Chirurgicaux</Label>
                                    <textarea
                                        id="antecedents_chirurgicaux"
                                        v-model="antecedentsForm.antecedents_chirurgicaux"
                                        rows="3"
                                        placeholder="ex: Appendicectomie en 2018..."
                                        class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                        :disabled="currentUser.role !== 'medecin' && currentUser.role !== 'admin'"
                                    ></textarea>
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <Label for="antecedents_familiaux">Antécédents Familiaux</Label>
                                    <textarea
                                        id="antecedents_familiaux"
                                        v-model="antecedentsForm.antecedents_familiaux"
                                        rows="2"
                                        placeholder="ex: HTA maternelle, AVC précoce paternel..."
                                        class="w-full rounded-md border border-input p-2 text-sm bg-transparent"
                                        :disabled="currentUser.role !== 'medecin' && currentUser.role !== 'admin'"
                                    ></textarea>
                                </div>
                            </div>

                            <div v-if="currentUser.role === 'medecin' || currentUser.role === 'admin'" class="flex justify-end pt-4">
                                <Button type="submit" :disabled="antecedentsForm.processing">
                                    Enregistrer les antécédents
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>

            <!-- ONGLET 3 : CONSULTATIONS & ORDONNANCES -->
            <div v-if="activeTab === 'consultations'" class="space-y-4">
                <div v-for="c in dossier.consultations" :key="c.id" class="border border-border rounded-lg p-5 bg-card space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-semibold text-foreground flex items-center gap-2">
                                <span>{{ c.motif_consultation }}</span>
                                <Badge v-if="c.est_cloturee" variant="secondary">Clôturée</Badge>
                                <Badge v-else variant="outline">En cours</Badge>
                            </div>
                            <div class="text-xs text-muted-foreground">
                                Réalisée le {{ new Date(c.date_heure).toLocaleString('fr-FR', { dateStyle: 'long', timeStyle: 'short' }) }} par {{ c.medecin.titre }} {{ c.medecin.user.name }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <Button v-if="c.ordonnance" as-child size="sm" variant="outline">
                                <Link :href="`/consultations/${c.id}/ordonnance`">
                                    <FileText class="mr-1 h-3.5 w-3.5 text-primary" />
                                    Imprimer Ordonnance
                                </Link>
                            </Button>
                            <Button as-child size="sm" variant="ghost">
                                <Link :href="`/consultations/${c.id}`">Voir Détails</Link>
                            </Button>
                        </div>
                    </div>

                    <div class="p-3 bg-muted/30 rounded text-sm">
                        <span class="font-semibold text-xs text-muted-foreground block">Diagnostic clinique :</span>
                        {{ c.diagnostic }}
                    </div>

                    <!-- Prescriptions résumées -->
                    <div v-if="c.ordonnance && c.ordonnance.prescriptions.length > 0" class="pt-2 border-t border-border/50 text-xs">
                        <span class="font-semibold text-muted-foreground block mb-1">Médicaments prescrits :</span>
                        <ul class="list-disc list-inside space-x-2 text-foreground flex flex-wrap">
                            <li v-for="p in c.ordonnance.prescriptions" :key="p.id">
                                <span class="font-medium">{{ p.nom_medicament }}</span> ({{ p.posologie }} - {{ p.duree }})
                            </li>
                        </ul>
                    </div>
                </div>

                <div v-if="dossier.consultations.length === 0" class="p-8 text-center text-muted-foreground border border-dashed rounded-lg">
                    Aucune consultation passée enregistrée dans ce dossier.
                </div>
            </div>

            <!-- ONGLET 4 : DOCUMENTS MÉDICAUX -->
            <div v-if="activeTab === 'documents'" class="space-y-6">
                <!-- Formulaire Upload -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg flex items-center gap-2">
                            <Upload class="h-5 w-5 text-primary" />
                            Joindre un document médical
                        </CardTitle>
                        <CardDescription>Résultats d'analyses, compte-rendu radiologique, électrocardiogramme (PDF ou Images max 10MB).</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submitDocument" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div class="space-y-1">
                                <Label for="doc_nom" class="text-xs">Nom du document *</Label>
                                <Input id="doc_nom" v-model="documentForm.nom" required placeholder="ex: Bilan lipidique" />
                            </div>
                            <div class="space-y-1">
                                <Label for="doc_type" class="text-xs">Type de document *</Label>
                                <select id="doc_type" v-model="documentForm.type_document" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                    <option value="Bilan biologique">Bilan biologique</option>
                                    <option value="Radiologie / Imagerie">Radiologie / Imagerie</option>
                                    <option value="Compte-rendu opératoire">Compte-rendu opératoire</option>
                                    <option value="Certificat médical">Certificat médical</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="doc_file" class="text-xs">Fichier *</Label>
                                <input
                                    id="doc_file"
                                    type="file"
                                    required
                                    @change="(e: any) => documentForm.fichier = e.target.files[0]"
                                    class="text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-primary file:text-primary-foreground"
                                />
                            </div>
                            <div class="md:col-span-3 flex justify-end">
                                <Button type="submit" :disabled="documentForm.processing">Téléverser le document</Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <!-- Liste des documents -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <div v-for="doc in dossier.documents" :key="doc.id" class="p-4 border border-border rounded-lg bg-card flex flex-col justify-between space-y-3">
                        <div>
                            <div class="font-semibold text-sm truncate" :title="doc.nom">{{ doc.nom }}</div>
                            <div class="text-xs text-muted-foreground">{{ doc.type_document }}</div>
                            <div class="text-[11px] text-muted-foreground/70 mt-1">Ajouté le {{ new Date(doc.created_at).toLocaleDateString('fr-FR') }}</div>
                        </div>

                        <Button as-child size="sm" variant="outline" class="w-full">
                            <a :href="`/storage/${doc.chemin_fichier}`" target="_blank" download>
                                <Download class="mr-1.5 h-3.5 w-3.5" />
                                Télécharger
                            </a>
                        </Button>
                    </div>

                    <div v-if="dossier.documents.length === 0" class="col-span-full p-8 text-center text-muted-foreground border border-dashed rounded-lg">
                        Aucun document médical joint à ce jour.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
