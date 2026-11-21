<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Edit3, FileText, ArrowLeft, Calendar, Phone, Mail, MapPin } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

defineProps<{
    patient: {
        id: number;
        numero_dossier: string;
        date_naissance: string | null;
        genre: string;
        groupe_sanguin: string | null;
        profession: string | null;
        contact_urgence_nom: string | null;
        contact_urgence_tel: string | null;
        created_at: string;
        user: {
            id: number;
            name: string;
            email: string;
            telephone: string | null;
            adresse: string | null;
        };
        dossier_medical?: {
            id: number;
        };
    };
}>();
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Patients', href: '/patients' }, { title: patient.user.name, href: '#' }]">
        <Head :title="`Patient - ${patient.user.name}`" />

        <div class="max-w-4xl mx-auto p-6 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Button variant="ghost" size="icon" as-child>
                        <Link href="/patients"><ArrowLeft class="h-4 w-4" /></Link>
                    </Button>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-bold tracking-tight">{{ patient.user.name }}</h1>
                            <Badge variant="outline" class="font-mono">{{ patient.numero_dossier }}</Badge>
                            <Badge v-if="patient.groupe_sanguin" variant="secondary">{{ patient.groupe_sanguin }}</Badge>
                        </div>
                        <p class="text-sm text-muted-foreground">Fiche administrative du patient</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <Button v-if="patient.dossier_medical" as-child class="bg-emerald-600 hover:bg-emerald-700 text-white">
                        <Link :href="`/dossiers/${patient.dossier_medical.id}`">
                            <FileText class="mr-2 h-4 w-4" />
                            Ouvrir Dossier Médical
                        </Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="`/patients/${patient.id}/edit`">
                            <Edit3 class="mr-2 h-4 w-4" />
                            Modifier
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Coordonnées -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Coordonnées de contact</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 text-sm">
                        <div class="flex items-center gap-3">
                            <Mail class="h-4 w-4 text-muted-foreground" />
                            <div>
                                <span class="text-muted-foreground text-xs block">Email</span>
                                <span class="font-medium">{{ patient.user.email }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <Phone class="h-4 w-4 text-muted-foreground" />
                            <div>
                                <span class="text-muted-foreground text-xs block">Téléphone</span>
                                <span class="font-medium">{{ patient.user.telephone || 'Non renseigné' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <MapPin class="h-4 w-4 text-muted-foreground" />
                            <div>
                                <span class="text-muted-foreground text-xs block">Adresse</span>
                                <span class="font-medium">{{ patient.user.adresse || 'Non renseignée' }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Informations Personnelles & Urgence -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">État civil & Urgence</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4 text-sm">
                        <div>
                            <span class="text-muted-foreground text-xs block">Date de naissance & Âge</span>
                            <span class="font-medium">{{ patient.date_naissance || 'Non renseignée' }}</span>
                        </div>
                        <div>
                            <span class="text-muted-foreground text-xs block">Profession</span>
                            <span class="font-medium">{{ patient.profession || 'Sans profession' }}</span>
                        </div>
                        <div class="pt-2 border-t border-border">
                            <span class="text-muted-foreground text-xs block">Contact d'urgence</span>
                            <div class="font-medium text-foreground">{{ patient.contact_urgence_nom || 'Non renseigné' }}</div>
                            <div class="text-xs text-muted-foreground">{{ patient.contact_urgence_tel || '' }}</div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
