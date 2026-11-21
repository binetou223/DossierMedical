<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';

const form = useForm({
    name: '',
    email: '',
    password: 'Password@2026',
    telephone: '+221',
    adresse: '',
    date_naissance: '',
    genre: 'M',
    groupe_sanguin: 'O+',
    profession: '',
    contact_urgence_nom: '',
    contact_urgence_tel: '',
});

function submit() {
    form.post('/patients');
}
</script>

<template>
    <AppLayout :breadcrumbs="[{ title: 'Patients', href: '/patients' }, { title: 'Nouveau Patient', href: '#' }]">
        <Head title="Nouveau Patient" />

        <div class="max-w-4xl mx-auto p-6 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">Enregistrement Patient</h1>
                    <p class="text-sm text-muted-foreground">Création du compte et ouverture automatique du dossier médical électronique.</p>
                </div>
                <Button variant="outline" as-child>
                    <Link href="/patients">Retour</Link>
                </Button>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section État Civil -->
                <Card>
                    <CardHeader>
                        <CardTitle>Informations Générales & Connexion</CardTitle>
                        <CardDescription>Données du compte utilisateur et identité du patient.</CardDescription>
                    </CardHeader>
                    <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <Label for="name">Nom et Prénom *</Label>
                            <Input id="name" v-model="form.name" required placeholder="ex: Fatou Diop" />
                            <span v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Adresse Email *</Label>
                            <Input id="email" type="email" v-model="form.email" required placeholder="fatou.diop@example.sn" />
                            <span v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</span>
                        </div>

                        <div class="space-y-2">
                            <Label for="telephone">Téléphone</Label>
                            <Input id="telephone" v-model="form.telephone" placeholder="+221 77 123 45 67" />
                        </div>

                        <div class="space-y-2">
                            <Label for="adresse">Adresse de résidence</Label>
                            <Input id="adresse" v-model="form.adresse" placeholder="Dakar, Mermoz" />
                        </div>

                        <div class="space-y-2">
                            <Label for="password">Mot de passe provisoire *</Label>
                            <Input id="password" type="password" v-model="form.password" required />
                        </div>

                        <div class="space-y-2">
                            <Label for="profession">Profession</Label>
                            <Input id="profession" v-model="form.profession" placeholder="Enseignante" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Section Médicale & Urgence -->
                <Card>
                    <CardHeader>
                        <CardTitle>Profil Sanitaire & Contact d'Urgence</CardTitle>
                        <CardDescription>Données initiales pour la prise en charge clinique.</CardDescription>
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
                            <Label for="contact_urgence_nom">Contact d'Urgence (Nom)</Label>
                            <Input id="contact_urgence_nom" v-model="form.contact_urgence_nom" placeholder="Mamadou Diop (Époux)" />
                        </div>

                        <div class="space-y-2">
                            <Label for="contact_urgence_tel">Contact d'Urgence (Téléphone)</Label>
                            <Input id="contact_urgence_tel" v-model="form.contact_urgence_tel" placeholder="+221 70 000 00 00" />
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-3">
                    <Button type="button" variant="outline" as-child>
                        <Link href="/patients">Annuler</Link>
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Enregistrement...' : 'Créer le patient et le dossier' }}
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
