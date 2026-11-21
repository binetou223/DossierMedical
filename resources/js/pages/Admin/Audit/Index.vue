<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { ShieldAlert, Search, Filter } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    logs: {
        data: Array<{
            id: number;
            action: string;
            entite_type: string;
            entite_id: number;
            description: string;
            ip_address: string | null;
            user_agent: string | null;
            created_at: string;
            user: {
                name: string;
                email: string;
                role: string;
            };
        }>;
        links: Array<any>;
        total: number;
    };
    actions: Array<string>;
    filters: {
        action: string | null;
        entite_type: string | null;
        user_id: number | null;
    };
}>();

const selectedAction = ref(props.filters.action || '');
const selectedEntite = ref(props.filters.entite_type || '');

function applyFilters() {
    router.get('/admin/audit', {
        action: selectedAction.value || undefined,
        entite_type: selectedEntite.value || undefined,
    }, { preserveState: true });
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Administration', href: '/admin/dashboard' }, { title: 'Journal d\'Audit', href: '#' }]">
        <Head title="Journal d'Audit - Sécurité & CDP" />

        <div class="space-y-6 p-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight flex items-center gap-2">
                    <ShieldAlert class="h-6 w-6 text-primary" />
                    Journal d'Audit & Traçabilité des Données de Santé
                </h1>
                <p class="text-sm text-muted-foreground">
                    Registre légal immuable conforme à la Commission des Données Personnelles (CDP) du Sénégal.
                </p>
            </div>

            <!-- Filtres -->
            <Card>
                <CardContent class="pt-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-medium text-muted-foreground mb-1 block">Filtrer par Action</label>
                            <select
                                v-model="selectedAction"
                                @change="applyFilters"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                            >
                                <option value="">Toutes les actions</option>
                                <option v-for="act in actions" :key="act" :value="act">{{ act }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-muted-foreground mb-1 block">Filtrer par Entité</label>
                            <select
                                v-model="selectedEntite"
                                @change="applyFilters"
                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                            >
                                <option value="">Toutes les entités</option>
                                <option value="DossierMedical">DossierMedical</option>
                                <option value="Consultation">Consultation</option>
                                <option value="RendezVous">RendezVous</option>
                                <option value="Patient">Patient</option>
                                <option value="Ordonnance">Ordonnance</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <Button variant="outline" class="w-full" @click="selectedAction = ''; selectedEntite = ''; applyFilters();">
                                Réinitialiser les filtres
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Table des logs d'audit -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Traces d'accès ({{ logs.total }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-border bg-muted/50 text-xs uppercase font-semibold text-muted-foreground">
                                <tr>
                                    <th class="p-3">Horodatage</th>
                                    <th class="p-3">Utilisateur</th>
                                    <th class="p-3">Action</th>
                                    <th class="p-3">Entité Ciblée</th>
                                    <th class="p-3">Description</th>
                                    <th class="p-3">Adresse IP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border text-xs">
                                <tr v-for="log in logs.data" :key="log.id" class="hover:bg-muted/30">
                                    <td class="p-3 text-muted-foreground font-mono whitespace-nowrap">
                                        {{ new Date(log.created_at).toLocaleString('fr-FR') }}
                                    </td>
                                    <td class="p-3">
                                        <div class="font-medium text-foreground">{{ log.user.name }}</div>
                                        <div class="text-[11px] text-muted-foreground capitalize">{{ log.user.role }}</div>
                                    </td>
                                    <td class="p-3 font-mono">
                                        <Badge variant="outline" class="text-[11px]">{{ log.action }}</Badge>
                                    </td>
                                    <td class="p-3">
                                        <span class="font-medium">{{ log.entite_type }}</span> #{{ log.entite_id }}
                                    </td>
                                    <td class="p-3 max-w-xs text-foreground">{{ log.description }}</td>
                                    <td class="p-3 font-mono text-muted-foreground">{{ log.ip_address || '127.0.0.1' }}</td>
                                </tr>
                                <tr v-if="logs.data.length === 0">
                                    <td colspan="6" class="p-8 text-center text-muted-foreground">
                                        Aucune trace d'audit trouvée.
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
