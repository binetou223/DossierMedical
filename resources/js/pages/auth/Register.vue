<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';

defineProps<{
    passwordRules?: string;
}>();

const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-light-surface text-light-on-surface font-body antialiased">
        <Head title="Inscription Patient — SanteLink DME" />

        <!-- Panneau gauche informatif médical -->
        <aside class="lg:col-span-5 bg-light-primary text-light-on-primary p-8 lg:p-14 flex flex-col justify-between gap-10">
            <div class="flex flex-col gap-8">
                <Link href="/" class="inline-flex items-center gap-3 self-start focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-light-on-primary rounded-md group">
                    <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-light-on-primary text-light-primary shadow-sm group-hover:bg-white/90 transition-colors">
                        <span class="material-symbols-outlined text-[24px]">medical_services</span>
                    </span>
                    <div class="flex flex-col">
                        <span class="font-headline text-xl text-light-on-primary font-bold">SanteLink DME</span>
                        <span class="text-xs text-light-secondary-container">Dossier Médical Électronique</span>
                    </div>
                </Link>

                <div class="flex flex-col gap-4 pt-4">
                    <div class="inline-flex items-center gap-2 self-start px-3 py-1 rounded-full bg-light-primary-container text-light-on-primary-container text-xs font-semibold">
                        <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                        <span>Création de compte patient</span>
                    </div>
                    <h1 class="font-display text-3xl lg:text-4xl text-light-on-primary font-bold leading-tight">
                        Votre santé à portée de main.
                    </h1>
                    <p class="font-body text-base text-light-on-primary-container/90 leading-relaxed">
                        Rejoignez SanteLink DME pour prendre vos rendez-vous médicaux en ligne, consulter vos ordonnances et conserver un historique complet de vos soins.
                    </p>
                </div>
            </div>

            <div class="bg-light-primary-container/80 rounded-xl p-5 border border-white/10 flex flex-col gap-2.5 backdrop-blur-xs">
                <div class="flex items-center gap-2 text-light-on-primary">
                    <span class="material-symbols-outlined text-[20px]">verified_user</span>
                    <span class="font-title text-sm font-semibold">Confidentialité & Secret Médical</span>
                </div>
                <p class="font-body text-xs text-light-on-primary-container/90 leading-relaxed">
                    Vos données sont confidentielles, stockées de manière sécurisée et traitées dans le strict respect de la réglementation sénégalaise sur les données de santé (CDP).
                </p>
            </div>
        </aside>

        <!-- Panneau droit formulaire -->
        <main class="lg:col-span-7 flex items-center justify-center p-6 sm:p-10 lg:p-14 bg-light-surface">
            <div class="w-full max-w-md flex flex-col gap-6">

                <!-- Sélecteur d'onglets -->
                <div class="grid grid-cols-2 p-1 rounded-xl bg-light-surface-container-low border border-light-outline-variant/40">
                    <Link
                        href="/login"
                        class="py-2.5 px-3 rounded-lg text-light-on-surface-variant hover:text-light-on-surface font-label text-sm font-semibold transition-colors text-center"
                    >
                        Connexion
                    </Link>
                    <div class="py-2.5 px-3 rounded-lg bg-light-surface-container-lowest text-light-primary font-label text-sm font-semibold shadow-xs text-center">
                        Inscription Patient
                    </div>
                </div>

                <!-- Formulaire Inscription -->
                <form
                    @submit.prevent="submit"
                    class="bg-light-surface-container-lowest rounded-2xl p-6 sm:p-8 border border-light-outline-variant/40 shadow-xs flex flex-col gap-4"
                >
                    <div class="flex flex-col gap-1">
                        <h2 class="font-headline text-2xl text-light-on-surface font-bold">Créer mon compte patient</h2>
                        <p class="font-body text-sm text-light-on-surface-variant">Remplissez vos informations pour ouvrir votre accès.</p>
                    </div>

                    <!-- Nom complet -->
                    <div class="flex flex-col gap-1.5 w-full">
                        <label for="name" class="font-label text-xs font-semibold text-light-on-surface">Nom complet (Prénom & Nom)</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-light-outline text-[18px] pointer-events-none">person</span>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                autofocus
                                placeholder="Ex. Aminata Diallo"
                                class="w-full rounded-lg border border-light-outline-variant bg-light-surface-container-lowest pl-10 pr-3.5 py-2.5 font-body text-sm text-light-on-surface placeholder:text-light-outline focus:border-light-primary focus:ring-2 focus:ring-light-primary/20 focus:outline-none transition-all"
                            />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col gap-1.5 w-full">
                        <label for="email" class="font-label text-xs font-semibold text-light-on-surface">Adresse e-mail</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-light-outline text-[18px] pointer-events-none">mail</span>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="aminata.diallo@exemple.sn"
                                class="w-full rounded-lg border border-light-outline-variant bg-light-surface-container-lowest pl-10 pr-3.5 py-2.5 font-body text-sm text-light-on-surface placeholder:text-light-outline focus:border-light-primary focus:ring-2 focus:ring-light-primary/20 focus:outline-none transition-all"
                            />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Mot de passe -->
                    <div class="flex flex-col gap-1.5 w-full">
                        <label for="password" class="font-label text-xs font-semibold text-light-on-surface">Mot de passe</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-light-outline text-[18px] pointer-events-none">lock</span>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                placeholder="Minimum 8 caractères"
                                class="w-full rounded-lg border border-light-outline-variant bg-light-surface-container-lowest pl-10 pr-10 py-2.5 font-body text-sm text-light-on-surface focus:border-light-primary focus:ring-2 focus:ring-light-primary/20 focus:outline-none transition-all"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 text-light-outline hover:text-light-on-surface transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">
                                    {{ showPassword ? 'visibility_off' : 'visibility' }}
                                </span>
                            </button>
                        </div>
                        <InputError :message="form.errors.password" />
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="flex flex-col gap-1.5 w-full">
                        <label for="password_confirmation" class="font-label text-xs font-semibold text-light-on-surface">Confirmer le mot de passe</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-light-outline text-[18px] pointer-events-none">lock_clock</span>
                            <input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                placeholder="Retapez le mot de passe"
                                class="w-full rounded-lg border border-light-outline-variant bg-light-surface-container-lowest pl-10 pr-3.5 py-2.5 font-body text-sm text-light-on-surface focus:border-light-primary focus:ring-2 focus:ring-light-primary/20 focus:outline-none transition-all"
                            />
                        </div>
                        <InputError :message="form.errors.password_confirmation" />
                    </div>

                    <!-- Bouton Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-2 w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-light-primary text-light-on-primary shadow-sm hover:bg-light-primary-container font-label text-sm font-semibold transition-colors disabled:opacity-60 cursor-pointer"
                    >
                        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                        <span>Créer mon compte patient</span>
                    </button>
                </form>

                <div class="text-center text-xs text-light-on-surface-variant">
                    Vous avez déjà un compte ?
                    <Link href="/login" class="font-semibold text-light-primary hover:underline ml-1">Connectez-vous ici</Link>
                </div>
            </div>
        </main>
    </div>
</template>
