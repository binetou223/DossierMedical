<script setup lang="ts">
import { computed } from 'vue';
import { useForm, Head, Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const props = defineProps<{
    services: Array<{ id: number; nom: string; code: string }>;
    medecins: Array<{ id: number; specialite: string; titre: string; service_id?: number; user: { id: number; name: string } }>;
    patients?: Array<{ id: number; numero_dossier: string; user: { name: string } }>;
}>();

const page = usePage();
const currentUser = page.props.auth.user;

const defaultStartDate = new Date(Date.now() + 86400000).toISOString().slice(0, 16);

const form = useForm({
    patient_id: currentUser.role === 'patient' ? (currentUser.patient?.id || 1) : 1,
    medecin_id: props.medecins[0]?.id || 1,
    service_id: props.services[0]?.id || 1,
    date_heure_debut: defaultStartDate,
    motif: '',
    notes_internes: '',
});

function submit() {
    form.post('/rendez-vous');
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Rendez-vous', href: '/rendez-vous' }, { title: 'Prendre Rendez-vous', href: '#' }]">
        <Head title="Prendre un Rendez-vous" />

        <div class="max-w-2xl mx-auto p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Demande de Rendez-vous</h1>
                    <p class="text-sm text-muted-foreground">Sélectionnez le médecin, le service et le créneau souhaité.</p>
                </div>
                <Button variant="outline" as-child>
                    <Link href="/rendez-vous">Retour</Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Détails du Rendez-vous</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div v-if="form.errors.date_heure_debut" class="p-3 text-sm rounded bg-destructive/10 text-destructive">
                            {{ form.errors.date_heure_debut }}
                        </div>

                        <div class="space-y-2">
                            <Label for="service_id">Service Médical *</Label>
                            <select id="service_id" v-model="form.service_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                <option v-for="s in services" :key="s.id" :value="s.id">
                                    {{ s.nom }} ({{ s.code }})
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <Label for="medecin_id">Praticien / Médecin *</Label>
                            <select id="medecin_id" v-model="form.medecin_id" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                <option v-for="m in medecins" :key="m.id" :value="m.id">
                                    {{ m.titre }} {{ m.user.name }} - {{ m.specialite }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <Label for="date_heure_debut">Date et Heure du créneau *</Label>
                            <Input id="date_heure_debut" type="datetime-local" v-model="form.date_heure_debut" required />
                        </div>

                        <div class="space-y-2">
                            <Label for="motif">Motif de consultation *</Label>
                            <textarea
                                id="motif"
                                v-model="form.motif"
                                required
                                rows="3"
                                placeholder="ex: Suivi de tension artérielle, bilan annuel, douleurs..."
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-3 pt-4">
                            <Button type="button" variant="outline" as-child>
                                <Link href="/rendez-vous">Annuler</Link>
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Envoi...' : 'Confirmer la demande de RDV' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
