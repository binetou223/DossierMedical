<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Printer } from '@lucide/vue';
import { Button } from '@/components/ui/button';

defineProps<{
    consultation: {
        id: number;
        dossier_medical_id: number;
        date_heure: string;
        medecin: {
            titre: string;
            specialite: string;
            matricule?: string;
            user: { name: string; telephone?: string };
            service: { nom: string };
        };
        dossier_medical: {
            patient: {
                numero_dossier: string;
                date_naissance: string | null;
                genre: string;
                user: { name: string; telephone?: string };
            };
        };
        ordonnance?: {
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
            }>;
        };
    };
}>();

function printPage() {
    window.print();
}
</script>

<template>
    <div class="min-h-screen bg-neutral-100 dark:bg-neutral-900 py-8 px-4 print:p-0 print:bg-white text-neutral-900">
        <Head title="Ordonnance Médicale" />

        <!-- Barre d'action hors impression -->
        <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between print:hidden">
            <Button variant="outline" size="sm" as-child>
                <Link :href="`/consultations/${consultation.id}`">
                    <ArrowLeft class="mr-1.5 h-4 w-4" />
                    Retour à la consultation
                </Link>
            </Button>
            <Button size="sm" class="bg-primary text-primary-foreground" @click="printPage">
                <Printer class="mr-1.5 h-4 w-4" />
                Imprimer l'ordonnance (Ctrl+P)
            </Button>
        </div>

        <!-- Feuille d'ordonnance officielle -->
        <div class="max-w-3xl mx-auto bg-white p-10 border border-neutral-300 rounded shadow-md print:shadow-none print:border-none print:p-8">
            <!-- Entête Centre Hospitalier / Clinique -->
            <div class="border-b-2 border-neutral-800 pb-4 mb-6 flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold uppercase tracking-wide">Centre Hospitalier National Universitaire</h2>
                    <p class="text-xs text-neutral-600">Dossier Médical Électronique (DME) — République du Sénégal</p>
                    <p class="text-xs text-neutral-600">Service : {{ consultation.medecin.service?.nom || 'Médecine Générale' }}</p>
                </div>
                <div class="text-right">
                    <div class="text-sm font-bold">{{ consultation.medecin.titre }} {{ consultation.medecin.user.name }}</div>
                    <div class="text-xs text-neutral-600">{{ consultation.medecin.specialite }}</div>
                    <div v-if="consultation.medecin.matricule" class="text-xs font-mono text-neutral-500">Matricule : {{ consultation.medecin.matricule }}</div>
                </div>
            </div>

            <!-- Date et Réf -->
            <div class="flex justify-between items-center text-xs text-neutral-600 mb-6">
                <div>Réf. Ordonnance : <strong class="font-mono text-neutral-900">{{ consultation.ordonnance?.numero_reference }}</strong></div>
                <div>Fait à Dakar, le : <strong>{{ new Date(consultation.ordonnance?.date_ordonnance || consultation.date_heure).toLocaleDateString('fr-FR') }}</strong></div>
            </div>

            <!-- Informations Patient -->
            <div class="bg-neutral-50 p-4 rounded border border-neutral-200 mb-8 text-sm">
                <div class="flex justify-between">
                    <div>
                        <span class="text-neutral-500 text-xs">Patient(e) :</span>
                        <div class="font-bold text-base">{{ consultation.dossier_medical.patient.user.name }}</div>
                    </div>
                    <div class="text-right">
                        <span class="text-neutral-500 text-xs">N° Dossier :</span>
                        <div class="font-mono font-bold">{{ consultation.dossier_medical.patient.numero_dossier }}</div>
                    </div>
                </div>
            </div>

            <!-- Corps de l'ordonnance -->
            <div class="mb-12 space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider border-b pb-1 text-neutral-700">Prescriptions Médicamenteuses</h3>

                <div class="space-y-4">
                    <div
                        v-for="(p, idx) in consultation.ordonnance?.prescriptions"
                        :key="p.id"
                        class="pl-4 border-l-2 border-neutral-800"
                    >
                        <div class="font-bold text-base">
                            {{ idx + 1 }}. {{ p.nom_medicament }} <span v-if="p.dosage">({{ p.dosage }})</span>
                        </div>
                        <div class="text-sm text-neutral-700 mt-0.5">
                            Forme : {{ p.forme || 'Comprimé' }} • Posologie : <span class="font-medium">{{ p.posologie }}</span>
                        </div>
                        <div class="text-xs text-neutral-500 mt-0.5">
                            Durée : {{ p.duree }}
                        </div>
                    </div>
                </div>

                <div v-if="consultation.ordonnance?.instructions_generales" class="mt-6 pt-4 border-t text-xs italic text-neutral-600">
                    Note : {{ consultation.ordonnance.instructions_generales }}
                </div>
            </div>

            <!-- Signature et Cachet -->
            <div class="pt-8 flex justify-end">
                <div class="w-64 text-center">
                    <div class="text-xs uppercase tracking-wider text-neutral-600 mb-16">Signature et Cachet du Médecin</div>
                    <div class="font-serif font-bold text-sm">{{ consultation.medecin.titre }} {{ consultation.medecin.user.name }}</div>
                    <div class="text-[11px] text-neutral-500">{{ consultation.medecin.specialite }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
