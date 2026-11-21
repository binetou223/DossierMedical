<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle, FileText, Printer, Stethoscope, Clock, User, ShieldCheck } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    consultation: {
        id: number;
        dossier_medical_id: number;
        date_heure: string;
        motif_consultation: string;
        histoire_maladie: string | null;
        examen_physique: string | null;
        diagnostic: string;
        conduite_a_tenir: string | null;
        est_cloturee: boolean;
        cloturee_le: string | null;
        medecin: {
            titre: string;
            specialite: string;
            user: { name: string };
        };
        dossier_medical: {
            id: number;
            patient: {
                id: number;
                numero_dossier: string;
                user: { name: string; telephone: string | null };
            };
        };
        ordonnance?: {
            id: number;
            numero_reference: string;
            date_ordonnance: string;
            instructions_generales: string | null;
            prescriptions: Array<{
                id: number;
                nom_medicament: string;
                dosage: string | null;
                forme: string | null;
                posologie: string;
                duree: string;
                instructions: string | null;
            }>;
        };
    };
}>();

const page = usePage();
const currentUser = page.props.auth.user;

function cloturer() {
    if (confirm('Clôturer définitivement cette consultation ? Elle deviendra immuable (non modifiable).')) {
        router.patch(`/consultations/${props.consultation.id}/cloturer`);
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Dossier Médical', href: `/dossiers/${consultation.dossier_medical_id}` }, { title: 'Détails Consultation', href: '#' }]">
        <Head :title="`Consultation - ${consultation.dossier_medical.patient.user.name}`" />

        <div class="max-w-4xl mx-auto p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="`/dossiers/${consultation.dossier_medical_id}`"><ArrowLeft class="h-4 w-4" /></Link>
                    </Button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold tracking-tight">{{ consultation.motif_consultation }}</h1>
                            <Badge v-if="consultation.est_cloturee" variant="secondary" class="bg-emerald-100 text-emerald-800">
                                Clôturée
                            </Badge>
                            <Badge v-else variant="outline">En cours</Badge>
                        </div>
                        <p class="text-sm text-muted-foreground">
                            Patient : {{ consultation.dossier_medical.patient.user.name }} ({{ consultation.dossier_medical.patient.numero_dossier }})
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button v-if="consultation.ordonnance" as-child class="bg-primary text-primary-foreground">
                        <Link :href="`/consultations/${consultation.id}/ordonnance`">
                            <Printer class="mr-2 h-4 w-4" />
                            Imprimer l'Ordonnance
                        </Link>
                    </Button>

                    <Button
                        v-if="!consultation.est_cloturee && (currentUser.role === 'medecin' || currentUser.role === 'admin')"
                        variant="outline"
                        class="text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50"
                        @click="cloturer"
                    >
                        <ShieldCheck class="mr-2 h-4 w-4" />
                        Clôturer l'acte médical
                    </Button>
                </div>
            </div>

            <!-- Fiche Clinique -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Examen Clinique & Synthèse</CardTitle>
                    <CardDescription>
                        Consultation menée le {{ new Date(consultation.date_heure).toLocaleString('fr-FR') }} par {{ consultation.medecin.titre }} {{ consultation.medecin.user.name }} ({{ consultation.medecin.specialite }})
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-4 text-sm">
                    <div v-if="consultation.histoire_maladie">
                        <span class="font-semibold text-xs text-muted-foreground block">Histoire de la maladie :</span>
                        <p class="mt-0.5 text-foreground">{{ consultation.histoire_maladie }}</p>
                    </div>

                    <div v-if="consultation.examen_physique">
                        <span class="font-semibold text-xs text-muted-foreground block">Examen physique :</span>
                        <p class="mt-0.5 text-foreground">{{ consultation.examen_physique }}</p>
                    </div>

                    <div class="p-3.5 bg-primary/5 border border-primary/20 rounded-lg">
                        <span class="font-semibold text-xs text-primary block">Diagnostic Posé :</span>
                        <p class="mt-1 text-foreground font-medium text-base">{{ consultation.diagnostic }}</p>
                    </div>

                    <div v-if="consultation.conduite_a_tenir">
                        <span class="font-semibold text-xs text-muted-foreground block">Conduite à tenir :</span>
                        <p class="mt-0.5 text-foreground">{{ consultation.conduite_a_tenir }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Ordonnance Associée -->
            <Card v-if="consultation.ordonnance">
                <CardHeader>
                    <CardTitle class="text-lg flex items-center justify-between">
                        <span>Ordonnance Médicale</span>
                        <span class="font-mono text-sm font-normal text-muted-foreground">{{ consultation.ordonnance.numero_reference }}</span>
                    </CardTitle>
                </CardHeader>
                <CardContent class="space-y-3">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-border bg-muted/50 text-xs uppercase font-semibold text-muted-foreground">
                                <tr>
                                    <th class="p-2.5">Médicament</th>
                                    <th class="p-2.5">Dosage / Forme</th>
                                    <th class="p-2.5">Posologie</th>
                                    <th class="p-2.5">Durée</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr v-for="p in consultation.ordonnance.prescriptions" :key="p.id">
                                    <td class="p-2.5 font-medium">{{ p.nom_medicament }}</td>
                                    <td class="p-2.5 text-muted-foreground">{{ p.dosage || '-' }} ({{ p.forme || '-' }})</td>
                                    <td class="p-2.5">{{ p.posologie }}</td>
                                    <td class="p-2.5">{{ p.duree }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="consultation.ordonnance.instructions_generales" class="text-xs text-muted-foreground pt-2">
                        <strong>Remarque :</strong> {{ consultation.ordonnance.instructions_generales }}
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
