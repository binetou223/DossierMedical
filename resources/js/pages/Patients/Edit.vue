<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps<{
    patient: {
        id: number;
        numero_dossier: string;
        date_naissance: string | null;
        genre: string;
        groupe_sanguin: string | null;
        profession: string | null;
        contact_urgence_nom: string | null;
        contact_urgence_tel: string | null;
        user: {
            id: number;
            name: string;
            telephone: string | null;
            adresse: string | null;
        };
    };
}>();

const form = useForm({
    name: props.patient.user.name,
    telephone: props.patient.user.telephone || '',
    adresse: props.patient.user.adresse || '',
    date_naissance: props.patient.date_naissance || '',
    genre: props.patient.genre,
    groupe_sanguin: props.patient.groupe_sanguin || 'O+',
    profession: props.patient.profession || '',
    contact_urgence_nom: props.patient.contact_urgence_nom || '',
    contact_urgence_tel: props.patient.contact_urgence_tel || '',
});

function submit() {
    form.put(`/patients/${props.patient.id}`);
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Patients', href: '/patients' }, { title: 'Modification', href: '#' }]">
        <Head title="Modifier le Patient" />

        <div class="max-w-4xl mx-auto p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Modifier Patient</h1>
                    <p class="text-sm text-muted-foreground">Mise à jour des données administratives de {{ patient.user.name }}.</p>
                </div>
                <Button variant="outline" as-child>
                    <Link :href="`/patients/${patient.id}`">Retour</Link>
                </Button>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Coordonnées</CardTitle>
                    </CardHeader>
                    <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="name">Nom et Prénom</Label>
                            <Input id="name" v-model="form.name" required />
                        </div>
                        <div class="space-y-2">
                            <Label for="telephone">Téléphone</Label>
                            <Input id="telephone" v-model="form.telephone" />
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <Label for="adresse">Adresse</Label>
                            <Input id="adresse" v-model="form.adresse" />
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Informations Personnelles & Urgence</CardTitle>
                    </CardHeader>
                    <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="date_naissance">Date de Naissance</Label>
                            <Input id="date_naissance" type="date" v-model="form.date_naissance" />
                        </div>
                        <div class="space-y-2">
                            <Label for="genre">Genre</Label>
                            <select id="genre" v-model="form.genre" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="groupe_sanguin">Groupe Sanguin</Label>
                            <select id="groupe_sanguin" v-model="form.groupe_sanguin" class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <Label for="profession">Profession</Label>
                            <Input id="profession" v-model="form.profession" />
                        </div>
                        <div class="space-y-2">
                            <Label for="contact_urgence_nom">Contact d'Urgence (Nom)</Label>
                            <Input id="contact_urgence_nom" v-model="form.contact_urgence_nom" />
                        </div>
                        <div class="space-y-2">
                            <Label for="contact_urgence_tel">Contact d'Urgence (Téléphone)</Label>
                            <Input id="contact_urgence_tel" v-model="form.contact_urgence_tel" />
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-3">
                    <Button type="button" variant="outline" as-child>
                        <Link :href="`/patients/${patient.id}`">Annuler</Link>
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Enregistrement...' : 'Sauvegarder les modifications' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
