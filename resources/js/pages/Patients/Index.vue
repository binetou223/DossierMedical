<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, UserPlus, Eye, Edit3, FileText } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    patients: {
        data: Array<{
            id: number;
            numero_dossier: string;
            genre: string;
            groupe_sanguin: string | null;
            profession: string | null;
            user: {
                id: number;
                name: string;
                email: string;
                telephone: string | null;
            };
            dossier_medical?: {
                id: number;
            };
        }>;
        links: Array<any>;
        total: number;
    };
    filters: {
        search: string;
    };
}>();

const search = ref(props.filters.search || '');

function handleSearch() {
    router.get('/patients', { search: search.value }, { preserveState: true, replace: true });
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Patients', href: '/patients' }]">
        <Head title="Gestion des Patients" />

        <div class="space-y-6 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">Répertoire des Patients</h1>
                    <p class="text-sm text-muted-foreground">Consultez, enregistrez et gérez les dossiers administratifs des patients.</p>
                </div>

                <Button as-child class="bg-primary text-primary-foreground hover:bg-primary/90">
                    <Link href="/patients/create">
                        <UserPlus class="mr-2 h-4 w-4" />
                        Nouveau Patient
                    </Link>
                </Button>
            </div>

            <!-- Barre de recherche -->
            <Card>
                <CardContent class="pt-6">
                    <form @submit.prevent="handleSearch" class="flex gap-2">
                        <div class="relative flex-1">
                            <Search class="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="search"
                                placeholder="Recherche par nom, email, téléphone ou numéro de dossier..."
                                class="pl-9"
                            />
                        </div>
                        <Button type="submit" variant="secondary">Rechercher</Button>
                    </form>
                </CardContent>
            </Card>

            <!-- Table des patients -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Patients enregistrés ({{ patients.total }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-border bg-muted/50 text-xs uppercase font-semibold text-muted-foreground">
                                <tr>
                                    <th class="p-3">N° Dossier</th>
                                    <th class="p-3">Patient</th>
                                    <th class="p-3">Contact</th>
                                    <th class="p-3">Genre / Groupe</th>
                                    <th class="p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr v-for="patient in patients.data" :key="patient.id" class="hover:bg-muted/30 transition-colors">
                                    <td class="p-3 font-mono font-medium text-primary">
                                        {{ patient.numero_dossier }}
                                    </td>
                                    <td class="p-3">
                                        <div class="font-medium text-foreground">{{ patient.user.name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ patient.profession || 'Sans profession' }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div>{{ patient.user.telephone || 'Non renseigné' }}</div>
                                        <div class="text-xs text-muted-foreground">{{ patient.user.email }}</div>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <Badge variant="outline">{{ patient.genre === 'F' ? 'Femme' : 'Homme' }}</Badge>
                                            <Badge v-if="patient.groupe_sanguin" variant="secondary" class="font-mono">
                                                {{ patient.groupe_sanguin }}
                                            </Badge>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right space-x-1">
                                        <Button v-if="patient.dossier_medical" as-child size="sm" variant="ghost" title="Voir Dossier Médical">
                                            <Link :href="`/dossiers/${patient.dossier_medical.id}`">
                                                <FileText class="h-4 w-4 text-emerald-600" />
                                            </Link>
                                        </Button>
                                        <Button as-child size="sm" variant="ghost" title="Détails administratifs">
                                            <Link :href="`/patients/${patient.id}`">
                                                <Eye class="h-4 w-4 text-muted-foreground" />
                                            </Link>
                                        </Button>
                                        <Button as-child size="sm" variant="ghost" title="Modifier">
                                            <Link :href="`/patients/${patient.id}/edit`">
                                                <Edit3 class="h-4 w-4 text-muted-foreground" />
                                            </Link>
                                        </Button>
                                    </td>
                                </tr>
                                <tr v-if="patients.data.length === 0">
                                    <td colspan="5" class="p-8 text-center text-muted-foreground">
                                        Aucun patient trouvé correspondant aux critères de recherche.
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
