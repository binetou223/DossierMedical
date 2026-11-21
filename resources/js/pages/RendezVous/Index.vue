<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { Calendar, Plus, CheckCircle2, XCircle, Stethoscope, Clock } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    rendezVous: {
        data: Array<{
            id: number;
            patient_id: number;
            medecin_id: number;
            service_id: number;
            date_heure_debut: string;
            date_heure_fin: string;
            motif: string;
            statut: string;
            motif_annulation?: string;
            patient: {
                id: number;
                numero_dossier: string;
                dossier_medical?: { id: number };
                user: { name: string; telephone?: string };
            };
            medecin: {
                id: number;
                titre: string;
                specialite: string;
                user: { name: string };
            };
            service: {
                id: number;
                nom: string;
            };
        }>;
        links: Array<any>;
        total: number;
    };
    upcoming?: Array<any>;
}>();

const page = usePage();
const currentUser = page.props.auth.user;

function confirmRdv(id: number) {
    if (confirm('Confirmer ce rendez-vous médical ?')) {
        router.patch(`/rendez-vous/${id}/confirm`);
    }
}

function cancelRdv(id: number) {
    const motif = prompt("Veuillez indiquer le motif d'annulation :");
    if (motif && motif.trim() !== '') {
        router.patch(`/rendez-vous/${id}/cancel`, { motif_annulation: motif });
    }
}

function getBadgeVariant(statut: string) {
    switch (statut) {
        case 'confirme':
            return 'default';
        case 'termine':
            return 'secondary';
        case 'annule':
            return 'destructive';
        default:
            return 'outline';
    }
}

function getStatusLabel(statut: string) {
    switch (statut) {
        case 'en_attente':
            return 'En attente';
        case 'confirme':
            return 'Confirmé';
        case 'termine':
            return 'Terminé';
        case 'annule':
            return 'Annulé';
        case 'absent':
            return 'Absent';
        default:
            return statut;
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Rendez-vous', href: '/rendez-vous' }]">
        <Head title="Gestion des Rendez-vous" />

        <div class="space-y-6 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Rendez-vous Médicaux</h1>
                    <p class="text-sm text-muted-foreground">Planification, confirmation et suivi des consultations.</p>
                </div>

                <Button as-child class="bg-primary text-primary-foreground hover:bg-primary/90">
                    <Link href="/rendez-vous/create">
                        <Plus class="mr-2 h-4 w-4" />
                        Nouveau Rendez-vous
                    </Link>
                </Button>
            </div>

            <!-- Liste des RDV -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Planning des consultations ({{ rendezVous.total }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-border bg-muted/50 text-xs uppercase font-semibold text-muted-foreground">
                                <tr>
                                    <th class="p-3">Date & Heure</th>
                                    <th class="p-3">Patient</th>
                                    <th class="p-3">Médecin / Service</th>
                                    <th class="p-3">Motif</th>
                                    <th class="p-3">Statut</th>
                                    <th class="p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr v-for="rdv in rendezVous.data" :key="rdv.id" class="hover:bg-muted/30 transition-colors">
                                    <td class="p-3">
                                        <div class="font-medium text-foreground flex items-center gap-1.5">
                                            <Clock class="h-3.5 w-3.5 text-muted-foreground" />
                                            {{ new Date(rdv.date_heure_debut).toLocaleString('fr-FR', { dateStyle: 'short', timeStyle: 'short' }) }}
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-medium">{{ rdv.patient.user.name }}</div>
                                        <div class="text-xs text-muted-foreground font-mono">{{ rdv.patient.numero_dossier }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-medium">{{ rdv.medecin.titre }} {{ rdv.medecin.user.name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ rdv.service.nom }} ({{ rdv.medecin.specialite }})</div>
                                    </td>
                                    <td class="p-3 max-w-xs truncate" :title="rdv.motif">
                                        {{ rdv.motif }}
                                    </td>
                                    <td class="p-3">
                                        <Badge :variant="getBadgeVariant(rdv.statut)">
                                            {{ getStatusLabel(rdv.statut) }}
                                        </Badge>
                                    </td>
                                    <td class="p-3 text-right space-x-1">
                                        <!-- Bouton pour le médecin : Commencer Consultation -->
                                        <Button
                                            v-if="(currentUser.role === 'medecin' || currentUser.role === 'admin') && (rdv.statut === 'confirme' || rdv.statut === 'en_attente')"
                                            as-child
                                            size="sm"
                                            class="bg-blue-600 hover:bg-blue-700 text-white"
                                            title="Réaliser la consultation"
                                        >
                                            <Link :href="`/consultations/create?dossier_id=${rdv.patient.dossier_medical?.id || rdv.patient.id}&rendez_vous_id=${rdv.id}`">
                                                <Stethoscope class="mr-1 h-3.5 w-3.5" />
                                                Consulter
                                            </Link>
                                        </Button>

                                        <!-- Bouton Confirmer pour Secrétaire/Médecin -->
                                        <Button
                                            v-if="rdv.statut === 'en_attente' && (currentUser.role === 'secretaire' || currentUser.role === 'medecin' || currentUser.role === 'admin')"
                                            size="sm"
                                            variant="outline"
                                            class="text-emerald-600 hover:text-emerald-700"
                                            @click="confirmRdv(rdv.id)"
                                            title="Confirmer"
                                        >
                                            <CheckCircle2 class="h-4 w-4" />
                                        </Button>

                                        <!-- Bouton Annuler -->
                                        <Button
                                            v-if="rdv.statut !== 'annule' && rdv.statut !== 'termine'"
                                            size="sm"
                                            variant="ghost"
                                            class="text-destructive hover:bg-destructive/10"
                                            @click="cancelRdv(rdv.id)"
                                            title="Annuler"
                                        >
                                            <XCircle class="h-4 w-4" />
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="rendezVous.data.length === 0">
                                    <td colspan="6" class="p-8 text-center text-muted-foreground">
                                        Aucun rendez-vous enregistré.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
