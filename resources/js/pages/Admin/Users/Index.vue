<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Search, UserCheck, UserX, Shield } from '@lucide/vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    users: {
        data: Array<{
            id: number;
            name: string;
            email: string;
            role: string;
            telephone: string | null;
            is_active: boolean;
            created_at: string;
        }>;
        links: Array<any>;
        total: number;
    };
    roles: Array<string>;
    services: Array<{ id: number; nom: string }>;
    filters: {
        search: string;
        role: string | null;
    };
}>();

const search = ref(props.filters.search || '');
const selectedRole = ref(props.filters.role || '');

function applySearch() {
    router.get('/admin/users', {
        search: search.value || undefined,
        role: selectedRole.value || undefined,
    }, { preserveState: true });
}

function toggleActive(userId: number) {
    router.patch(`/admin/users/${userId}/toggle-active`, {}, { preserveScroll: true });
}

function updateRole(userId: number, event: Event) {
    const target = event.target as HTMLSelectElement;
    const newRole = target.value;
    if (confirm(`Modifier le rôle de cet utilisateur vers "${newRole}" ?`)) {
        router.patch(`/admin/users/${userId}/role`, { role: newRole }, { preserveScroll: true });
    } else {
        router.reload();
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Administration', href: '/admin/dashboard' }, { title: 'Utilisateurs', href: '#' }]">
        <Head title="Gestion des Utilisateurs & Rôles" />

        <div class="space-y-6 p-6">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">Comptes Utilisateurs & Droits d'Accès</h1>
                <p class="text-sm text-muted-foreground">Gestion des permissions, assignation des rôles et contrôle d'activité.</p>
            </div>

            <!-- Filtres -->
            <Card>
                <CardContent class="pt-6">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <Search class="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                            <Input
                                v-model="search"
                                placeholder="Rechercher par nom, email ou téléphone..."
                                class="pl-9"
                                @keyup.enter="applySearch"
                            />
                        </div>
                        <select
                            v-model="selectedRole"
                            @change="applySearch"
                            class="flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm"
                        >
                            <option value="">Tous les rôles</option>
                            <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
                        </select>
                        <Button variant="secondary" @click="applySearch">Filtrer</Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Table Utilisateurs -->
            <Card>
                <CardHeader>
                    <CardTitle class="text-lg">Comptes enregistrés ({{ users.total }})</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-border bg-muted/50 text-xs uppercase font-semibold text-muted-foreground">
                                <tr>
                                    <th class="p-3">Utilisateur</th>
                                    <th class="p-3">Contact</th>
                                    <th class="p-3">Rôle Assigné</th>
                                    <th class="p-3">Statut</th>
                                    <th class="p-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <tr v-for="u in users.data" :key="u.id" class="hover:bg-muted/30">
                                    <td class="p-3 font-medium text-foreground">
                                        {{ u.name }}
                                        <div class="text-xs text-muted-foreground font-normal">Inscrit le {{ new Date(u.created_at).toLocaleDateString('fr-FR') }}</div>
                                    </td>
                                    <td class="p-3 text-xs">
                                        <div>{{ u.email }}</div>
                                        <div class="text-muted-foreground">{{ u.telephone || 'Sans tél' }}</div>
                                    </td>
                                    <td class="p-3">
                                        <select
                                            :value="u.role"
                                            @change="updateRole(u.id, $event)"
                                            class="text-xs font-medium rounded border border-input bg-transparent p-1 shadow-sm capitalize"
                                        >
                                            <option v-for="r in roles" :key="r" :value="r">{{ r }}</option>
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <Badge :variant="u.is_active ? 'default' : 'destructive'">
                                            {{ u.is_active ? 'Actif' : 'Désactivé' }}
                                        </Badge>
                                    </td>
                                    <td class="p-3 text-right">
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            :class="u.is_active ? 'text-destructive hover:bg-destructive/10' : 'text-emerald-600 hover:bg-emerald-50'"
                                            @click="toggleActive(u.id)"
                                        >
                                            {{ u.is_active ? 'Désactiver' : 'Activer' }}
                                        </Button>
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
