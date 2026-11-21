<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

onMounted(() => {
    const params = new URLSearchParams(window.location.search);
    const emailParam = params.get('email');
    if (emailParam) {
        form.email = emailParam;
        form.password = 'Password@2026';
    }
});

const selectRoleAccount = (email: string) => {
    form.email = email;
    form.password = 'Password@2026';
};

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-light-surface text-light-on-surface font-body antialiased">
        <Head title="Connexion — SanteLink DME" />

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
                        <span class="material-symbols-outlined text-[16px]">verified_user</span>
                        <span>Plateforme médicale certifiée</span>
                    </div>
                    <h1 class="font-display text-3xl lg:text-4xl text-light-on-primary font-bold leading-tight">
                        Votre espace santé, sécurisé par rôle.
                    </h1>
                    <p class="font-body text-base text-light-on-primary-container/90 leading-relaxed">
                        Connectez-vous pour gérer vos rendez-vous, consulter votre historique médical ou assurer le suivi clinique de vos patients.
                    </p>
                </div>
            </div>

            <div class="bg-light-primary-container/80 rounded-xl p-5 border border-white/10 flex flex-col gap-2.5 backdrop-blur-xs">
                <div class="flex items-center gap-2 text-light-on-primary">
                    <span class="material-symbols-outlined text-[20px]">shield_lock</span>
                    <span class="font-title text-sm font-semibold">Protection des données de santé</span>
                </div>
                <p class="font-body text-xs text-light-on-primary-container/90 leading-relaxed">
                    Authentification chiffrée, contrôle d'accès strict par rôle (Patient, Secrétaire, Médecin, Administrateur) et traçabilité inaltérable conforme à la Commission des Données Personnelles (CDP) du Sénégal.
                </p>
            </div>
        </aside>

        <!-- Panneau droit formulaires -->
        <main class="lg:col-span-7 flex items-center justify-center p-6 sm:p-10 lg:p-14 bg-light-surface">
            <div class="w-full max-w-md flex flex-col gap-6">

                <!-- Sélecteur d'onglets Connexion / Inscription -->
                <div class="grid grid-cols-2 p-1 rounded-xl bg-light-surface-container-low border border-light-outline-variant/40">
                    <div class="py-2.5 px-3 rounded-lg bg-light-surface-container-lowest text-light-primary font-label text-sm font-semibold shadow-xs text-center">
                        Connexion
                    </div>
                    <Link
                        href="/register"
                        class="py-2.5 px-3 rounded-lg text-light-on-surface-variant hover:text-light-on-surface font-label text-sm font-semibold transition-colors text-center"
                    >
                        Inscription Patient
                    </Link>
                </div>

                <div
                    v-if="status"
                    class="rounded-lg p-4 bg-emerald-50 text-emerald-800 border border-emerald-200 text-sm font-medium flex items-center gap-2"
                >
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    <span>{{ status }}</span>
                </div>

                <!-- Formulaire de Connexion -->
                <form
                    @submit.prevent="submit"
                    class="bg-light-surface-container-lowest rounded-2xl p-6 sm:p-8 border border-light-outline-variant/40 shadow-xs flex flex-col gap-5"
                >
                    <div class="flex flex-col gap-1">
                        <h2 class="font-headline text-2xl text-light-on-surface font-bold">Accéder à votre espace</h2>
                        <p class="font-body text-sm text-light-on-surface-variant">Saisissez vos identifiants SanteLink DME.</p>
                    </div>

                    <!-- Champ Email -->
                    <div class="flex flex-col gap-1.5 w-full">
                        <label for="email" class="font-label text-xs font-semibold text-light-on-surface">Adresse e-mail</label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-light-outline text-[18px] pointer-events-none">mail</span>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                autofocus
                                placeholder="nom@exemple.sn"
                                class="w-full rounded-lg border border-light-outline-variant bg-light-surface-container-lowest pl-10 pr-3.5 py-2.5 font-body text-sm text-light-on-surface placeholder:text-light-outline focus:border-light-primary focus:ring-2 focus:ring-light-primary/20 focus:outline-none transition-all"
                            />
                        </div>
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Champ Mot de passe -->
                    <div class="flex flex-col gap-1.5 w-full">
                        <div class="flex items-center justify-between">
                            <label for="password" class="font-label text-xs font-semibold text-light-on-surface">Mot de passe</label>
                            <Link
                                v-if="canResetPassword"
                                href="/forgot-password"
                                class="text-xs text-light-primary hover:underline font-medium"
                            >
                                Mot de passe oublié ?
                            </Link>
                        </div>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined absolute left-3.5 text-light-outline text-[18px] pointer-events-none">lock</span>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                placeholder="••••••••••••"
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

                    <!-- Sélecteur rapide par rôle (Prototype & Soutenance) -->
                    <div class="flex flex-col gap-2 pt-1 border-t border-light-outline-variant/30">
                        <div class="flex items-center justify-between">
                            <span class="font-label text-xs font-semibold text-light-on-surface">Accès rapide par rôle (Démo jury) :</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button
                                type="button"
                                @click="selectRoleAccount('medecin@dme.sn')"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border border-light-primary/30 bg-light-surface-container-low text-light-primary font-label text-xs font-semibold hover:bg-light-primary hover:text-white transition-all text-left"
                            >
                                <span class="material-symbols-outlined text-[16px]">stethoscope</span>
                                <span>Médecin</span>
                            </button>

                            <button
                                type="button"
                                @click="selectRoleAccount('patient@dme.sn')"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border border-light-outline-variant/60 text-light-on-surface-variant hover:text-light-primary hover:border-light-primary hover:bg-light-surface-container-low font-label text-xs font-semibold transition-all text-left"
                            >
                                <span class="material-symbols-outlined text-[16px]">person</span>
                                <span>Patient</span>
                            </button>

                            <button
                                type="button"
                                @click="selectRoleAccount('secretaire@dme.sn')"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border border-light-outline-variant/60 text-light-on-surface-variant hover:text-light-primary hover:border-light-primary hover:bg-light-surface-container-low font-label text-xs font-semibold transition-all text-left"
                            >
                                <span class="material-symbols-outlined text-[16px]">support_agent</span>
                                <span>Secrétaire</span>
                            </button>

                            <button
                                type="button"
                                @click="selectRoleAccount('admin@dme.sn')"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg border border-light-outline-variant/60 text-light-on-surface-variant hover:text-light-primary hover:border-light-primary hover:bg-light-surface-container-low font-label text-xs font-semibold transition-all text-left"
                            >
                                <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                                <span>Administrateur</span>
                            </button>
                        </div>
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="flex items-center">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded border-light-outline-variant text-light-primary focus:ring-light-primary/20"
                            />
                            <span class="text-xs text-light-on-surface-variant">Se souvenir de moi</span>
                        </label>
                    </div>

                    <!-- Bouton Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-lg bg-light-primary text-light-on-primary shadow-sm hover:bg-light-primary-container font-label text-sm font-semibold transition-colors disabled:opacity-60 cursor-pointer"
                    >
                        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                        <span>Se connecter à mon espace</span>
                    </button>
                </form>

                <div class="text-center text-xs text-light-on-surface-variant">
                    Besoin d'aide ? Contactez le support hospitalier au <span class="font-semibold text-light-primary">+221 33 800 00 00</span>
                </div>
            </div>
        </main>
    </div>
</template>
