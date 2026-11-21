<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Users, Stethoscope, FileText, Calendar, Activity, ShieldCheck, CheckCircle2, Clock } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

defineProps<{
    stats: {
        total_patients: number;
        total_medecins: number;
        total_consultations: number;
        total_services: number;
        rdv_en_attente: number;
        rdv_confirmes: number;
        rdv_termines: number;
        utilisateurs_actifs: number;
    };
    repartition: {
        patients: number;
        medecins: number;
        secretaires: number;
        admins: number;
    };
    recentAudits: Array<{
        id: number;
        action: string;
        entite_type: string;
        description: string;
        created_at: string;
        user: { name: string };
    }>;
}>();
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Administration', href: '/admin/dashboard' }, { title: 'Tableau de bord', href: '#' }]">
        <Head title="Administration - Tableau de bord" />

        <div class="space-y-6 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Supervision & Indicateurs de Pilotage</h1>
                    <p class="text-sm text-muted-foreground">Vue d'ensemble de l'activité hospitalière et sécurité du DME.</p>
                </div>

                <div class="flex items-center gap-2">
                    <Button variant="outline" as-child>
                        <Link href="/admin/audit">Consulter le Journal d'Audit (CDP)</Link>
                    </Button>
                    <Button as-child>
                        <Link href="/admin/users">Gérer les Utilisateurs</Link>
                    </Button>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Patients Suivis</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_patients }}</div>
                        <p class="text-xs text-muted-foreground">Dossiers médicaux actifs</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Médecins en Service</CardTitle>
                        <Stethoscope class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_medecins }}</div>
                        <p class="text-xs text-muted-foreground">Répartis sur {{ stats.total_services }} services</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Consultations Réalisées</CardTitle>
                        <FileText class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.total_consultations }}</div>
                        <p class="text-xs text-muted-foreground">Avec ordonnance sécurisée</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between pb-2">
                        <CardTitle class="text-sm font-medium">Rendez-vous en Attente</CardTitle>
                        <Calendar class="h-4 w-4 text-amber-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold text-amber-600">{{ stats.rdv_en_attente }}</div>
                        <p class="text-xs text-muted-foreground">{{ stats.rdv_confirmes }} confirmés • {{ stats.rdv_termines }} terminés</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Répartition des Comptes & Activité Récente -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Répartition des rôles -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">Comptes Utilisateurs par Rôle</CardTitle>
                        <CardDescription>Total utilisateurs actifs : {{ stats.utilisateurs_actifs }}</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="flex items-center justify-between p-3 rounded-lg bg-muted/40">
                            <span class="text-sm font-medium">Patients</span>
                            <Badge variant="outline">{{ repartition.patients }}</Badge>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-muted/40">
                            <span class="text-sm font-medium">Médecins & Spécialistes</span>
                            <Badge variant="outline">{{ repartition.medecins }}</Badge>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-muted/40">
                            <span class="text-sm font-medium">Secrétaires Médicales</span>
                            <Badge variant="outline">{{ repartition.secretaires }}</Badge>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-lg bg-muted/40">
                            <span class="text-sm font-medium">Administrateurs Système</span>
                            <Badge variant="outline">{{ repartition.admins }}</Badge>
                        </div>
                    </CardContent>
                </Card>

                <!-- Derniers événements d'audit -->
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between">
                        <div>
                            <CardTitle class="text-lg flex items-center gap-2">
                                <ShieldCheck class="h-5 w-5 text-emerald-600" />
                                Traçabilité & Audit (CDP Sénégal)
                            </CardTitle>
                            <CardDescription>Derniers accès et modifications tracés.</CardDescription>
                        </div>
                        <Button variant="ghost" size="sm" as-child>
                            <Link href="/admin/audit">Voir tout</Link>
                        </Button>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-3">
                            <div
                                v-for="log in recentAudits"
                                :key="log.id"
                                class="text-xs p-2.5 rounded border border-border/50 bg-card flex items-start justify-between gap-2"
                            >
                                <div>
                                    <div class="font-medium text-foreground">{{ log.description }}</div>
                                    <div class="text-muted-foreground mt-0.5">Par : <strong>{{ log.user.name }}</strong> • Action : {{ log.action }}</div>
                                </div>
                                <div class="text-[11px] text-muted-foreground whitespace-nowrap">
                                    {{ new Date(log.created_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
