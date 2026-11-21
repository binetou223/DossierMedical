<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const isPlaying = ref(true);
const openFaq = ref<number | null>(0);

const toggleFaq = (index: number) => {
    openFaq.value = openFaq.value === index ? null : index;
};

const demoAccounts = [
    {
        role: 'Patient(e)',
        name: 'Fatou Diop',
        email: 'patient@dme.sn',
        password: 'Password@2026',
        desc: 'Consultation du dossier médical, prise de rendez-vous en ligne, ordonnances téléchargeables.',
        badge: 'Espace Patient',
        badgeClass: 'bg-emerald-100 text-emerald-800 border-emerald-200',
        icon: 'person',
    },
    {
        role: 'Médecin Praticien',
        name: 'Dr Mamadou Ndiaye',
        email: 'medecin@dme.sn',
        password: 'Password@2026',
        desc: 'Examens cliniques, constantes avec alertes (Strategy Pattern), prescriptions et ordonnances.',
        badge: 'Cardiologie',
        badgeClass: 'bg-blue-100 text-blue-800 border-blue-200',
        icon: 'stethoscope',
    },
    {
        role: 'Secrétaire Médicale',
        name: 'Aïssatou Ba',
        email: 'secretaire@dme.sn',
        password: 'Password@2026',
        desc: 'Accueil hospitalier, enregistrement des patients, validation et planning des consultations.',
        badge: 'Accueil & Triage',
        badgeClass: 'bg-amber-100 text-amber-800 border-amber-200',
        icon: 'support_agent',
    },
    {
        role: 'Administrateur',
        name: 'Dr Ibrahima Diop',
        email: 'admin@dme.sn',
        password: 'Password@2026',
        desc: 'Supervision des indicateurs de santé, gestion des comptes et journal d\'audit légal CDP Sénégal.',
        badge: 'Supervision & Audit',
        badgeClass: 'bg-purple-100 text-purple-800 border-purple-200',
        icon: 'admin_panel_settings',
    },
];

const faqs = [
    {
        q: 'Qui peut consulter mon dossier médical électronique ?',
        a: 'Seul vous-même (en consultation sur votre espace patient) et les professionnels de santé qui assurent votre prise en charge directe peuvent accéder à votre dossier. Chaque accès est consigné de façon inaltérable dans le journal d\'audit légal (CDP Sénégal).',
    },
    {
        q: 'Comment se déroule la confirmation d\'un rendez-vous ?',
        a: 'Une fois votre demande soumise en ligne avec le médecin et la date souhaitée, le secrétariat médical vérifie l\'agenda du praticien et confirme ou réaménage le créneau. Vous recevez la notification directement dans votre espace.',
    },
    {
        q: 'Puis-je imprimer mes ordonnances depuis mon espace patient ?',
        a: 'Oui. Chaque consultation clôturée par votre médecin génère une ordonnance officielle structurée, sécurisée par référence unique, prête à être visualisée et imprimée à tout moment.',
    },
    {
        q: 'Comment sont protégées mes données de santé ?',
        a: 'La plateforme implémente un chiffrement complet des données, des règles d\'autorisation strictes par rôle (Policies), la protection CSRF/XSS et une conformité rigoureuse avec la législation sénégalaise sur les données personnelles.',
    },
];
</script>

<template>
    <div class="min-h-screen bg-light-surface text-light-on-surface font-body antialiased flex flex-col selection:bg-light-secondary-container selection:text-light-on-secondary-container">
        <Head title="SanteLink DME — Système de Gestion de Dossier Médical Électronique" />

        <!-- Navigation supérieure -->
        <header class="sticky top-0 z-40 bg-light-surface/95 backdrop-blur-md border-b border-light-outline-variant/40 shadow-xs">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between gap-6">
                <Link href="/" class="flex items-center gap-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-light-primary rounded-md group">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-light-primary text-light-on-primary shadow-sm group-hover:bg-light-primary-container transition-colors">
                        <span class="material-symbols-outlined text-[22px]" aria-hidden="true">medical_services</span>
                    </span>
                    <div class="flex flex-col">
                        <span class="font-headline text-title-lg text-light-on-surface leading-tight">SanteLink DME</span>
                        <span class="hidden sm:inline font-label text-label-sm text-light-secondary">Dossier Médical Électronique</span>
                    </div>
                </Link>

                <nav class="hidden md:flex items-center gap-8" aria-label="Navigation principale">
                    <a href="#avantages" class="font-label text-label-lg text-light-on-surface-variant hover:text-light-primary transition-colors">Avantages</a>
                    <a href="#fonctionnalites" class="font-label text-label-lg text-light-on-surface-variant hover:text-light-primary transition-colors">Fonctionnalités</a>
                    <a href="#demonstration" class="font-label text-label-lg text-light-on-surface-variant hover:text-light-primary transition-colors">Comptes Démo</a>
                    <a href="#fonctionnement" class="font-label text-label-lg text-light-on-surface-variant hover:text-light-primary transition-colors">Parcours</a>
                    <a href="#faq" class="font-label text-label-lg text-light-on-surface-variant hover:text-light-primary transition-colors">FAQ</a>
                </nav>

                <div class="flex items-center gap-3 shrink-0">
                    <template v-if="page.props.auth?.user">
                        <Link
                            href="/dashboard"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-md bg-light-primary text-light-on-primary shadow-sm hover:bg-light-primary-container font-label text-label-lg transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">dashboard</span>
                            <span>Accéder à mon espace</span>
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            href="/login"
                            class="hidden sm:inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-md bg-light-surface-container-lowest text-light-primary border border-light-outline-variant hover:bg-light-surface-container-low font-label text-label-lg transition-colors shadow-xs"
                        >
                            <span>Se connecter</span>
                        </Link>
                        <Link
                            href="/login"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-md bg-light-primary text-light-on-primary shadow-sm hover:bg-light-primary-container font-label text-label-lg transition-colors"
                        >
                            <span class="material-symbols-outlined text-[18px]">account_circle</span>
                            <span>Mon espace</span>
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <main class="flex-1">

            <!-- Section Hero avec Vidéo de la patiente -->
            <section class="relative overflow-hidden bg-light-surface-container-low/60 border-b border-light-outline-variant/30">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14 lg:py-20 grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    <div class="lg:col-span-5 flex flex-col gap-6">
                        <div class="inline-flex items-center gap-2 self-start px-3.5 py-1.5 rounded-full bg-light-secondary-container text-light-on-secondary-container text-xs font-semibold">
                            <span class="material-symbols-outlined text-[16px]" aria-hidden="true">verified_user</span>
                            <span>Plateforme médicale sécurisée • République du Sénégal</span>
                        </div>

                        <h1 class="font-display text-4xl lg:text-5xl text-light-on-surface leading-tight tracking-tight">
                            Un suivi médical humain, continu et sécurisé.
                        </h1>

                        <p class="font-body text-body-lg text-light-on-surface-variant max-w-prose leading-relaxed">
                            <strong>SanteLink DME</strong> centralise le dossier médical du patient et simplifie la prise de rendez-vous en ligne, pour offrir aux patients comme aux médecins une prise en charge fluide, rigoureuse et sereine.
                        </p>

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a
                                href="#demonstration"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-md bg-light-primary text-light-on-primary shadow-sm hover:bg-light-primary-container font-label text-label-lg transition-colors"
                            >
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">play_circle</span>
                                <span>Tester le scénario de démo</span>
                            </a>

                            <a
                                href="#fonctionnalites"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-md bg-light-surface-container-lowest text-light-primary border border-light-outline-variant hover:bg-light-surface-container-low font-label text-label-lg transition-colors shadow-xs"
                            >
                                <span>Découvrir les fonctionnalités</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-4 border-t border-light-outline-variant/40">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-headline text-2xl lg:text-3xl text-light-primary tabular-nums font-bold">100%</span>
                                <span class="font-body text-body-md text-light-on-surface-variant">Dossiers centralisés</span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-headline text-2xl lg:text-3xl text-light-secondary tabular-nums font-bold">4 rôles</span>
                                <span class="font-body text-body-md text-light-on-surface-variant">Accès cloisonnés</span>
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-headline text-2xl lg:text-3xl text-light-primary tabular-nums font-bold">24h/24</span>
                                <span class="font-body text-body-md text-light-on-surface-variant">Historique accessible</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lecteur Visuel Héro de la patiente -->
                    <div class="lg:col-span-7">
                        <div class="relative rounded-2xl overflow-hidden border border-light-outline-variant/40 shadow-xl bg-light-inverse-surface">
                            <div class="relative aspect-video overflow-hidden">
                                <img
                                    src="https://lh3.googleusercontent.com/aida/ALJJyrCVLfQTGWzP3WGbgCowpRnVXGuAaobeOdL3CY7ADjfpiMNfzTF7ZZvRkV-2bHK9o1BAwo5F9Fu2v6ZithuioLuWvv4neKFscb9cTl7dmCRSRiJyHlS4NyimNq21C145uGbImwdsTN8Ve0KAlXwqdqDx4QPVUbgQwr5FwrBwQtSp0HBwMRiugGxArn-ceyX23srtjC7UBF9e_REgSooMrjq3ncR307XdngTo66OtMGRCVWQJ9zYAumHFFU5k"
                                    alt="Patiente sereine en consultation dans un environnement médical moderne avec son médecin"
                                    class="w-full h-full object-cover transition-transform duration-700 hover:scale-105"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent pointer-events-none"></div>

                                <div class="absolute top-4 left-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/95 text-light-on-surface shadow-sm backdrop-blur-xs">
                                    <span class="material-symbols-outlined text-light-secondary text-[16px]">videocam</span>
                                    <span class="font-label text-label-sm font-semibold">Expérience patiente — Clinique moderne</span>
                                </div>

                                <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between gap-4 bg-white/95 rounded-xl px-4 py-3 shadow-lg backdrop-blur-xs">
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            @click="isPlaying = !isPlaying"
                                            class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-light-primary text-light-on-primary hover:bg-light-primary-container transition-colors shadow-sm"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">{{ isPlaying ? 'pause' : 'play_arrow' }}</span>
                                        </button>
                                        <div class="flex flex-col">
                                            <span class="font-title text-title-md text-light-on-surface font-semibold">Consultation apaisée & dossier partagé</span>
                                            <span class="font-body text-body-md text-light-on-surface-variant">Parcours patient SanteLink • Dakar, Sénégal</span>
                                        </div>
                                    </div>
                                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-label text-label-sm font-semibold border border-emerald-200">
                                        <span class="material-symbols-outlined text-[16px]">lock</span>
                                        Secret médical garanti
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Démonstration Soutenance (Comptes de test) -->
            <section id="demonstration" class="py-16 lg:py-20 bg-light-surface scroll-mt-20">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col gap-8">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-light-outline-variant/40 pb-5">
                        <div class="space-y-1">
                            <span class="font-mono text-xs uppercase tracking-wider text-light-secondary font-semibold">Scénario officiel de soutenance</span>
                            <h2 class="font-display text-3xl lg:text-4xl text-light-on-surface font-bold">
                                Comptes de Démonstration Clé en Main
                            </h2>
                            <p class="font-body text-body-lg text-light-on-surface-variant">
                                Cliquez directement sur un profil pour vous connecter avec les identifiants préconfigurés.
                            </p>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-light-surface-container-low border border-light-outline-variant font-mono text-xs text-light-primary">
                            <span class="material-symbols-outlined text-[16px]">key</span>
                            <span>Mot de passe unique : <strong>Password@2026</strong></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            v-for="acc in demoAccounts"
                            :key="acc.email"
                            class="bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs hover:shadow-md hover:border-light-primary/50 transition-all flex flex-col justify-between gap-5 group"
                        >
                            <div class="flex flex-col gap-3">
                                <div class="flex items-center justify-between">
                                    <span :class="['px-2.5 py-1 rounded-full text-xs font-semibold border', acc.badgeClass]">{{ acc.badge }}</span>
                                    <span class="text-xs font-bold text-light-on-surface-variant">{{ acc.role }}</span>
                                </div>
                                <div>
                                    <h3 class="font-title text-lg text-light-on-surface font-semibold group-hover:text-light-primary transition-colors">{{ acc.name }}</h3>
                                    <p class="font-body text-body-md text-light-on-surface-variant mt-1 min-h-[44px] leading-relaxed">{{ acc.desc }}</p>
                                </div>
                            </div>

                            <div class="space-y-3 pt-3 border-t border-light-outline-variant/30">
                                <div class="p-2.5 rounded-md bg-light-surface-container-low border border-light-outline-variant/30 font-mono text-xs space-y-0.5">
                                    <div class="truncate text-light-on-surface font-semibold">Email : {{ acc.email }}</div>
                                    <div class="text-light-on-surface-variant">Mdp : {{ acc.password }}</div>
                                </div>

                                <Link
                                    :href="`/login?email=${encodeURIComponent(acc.email)}`"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-md bg-light-primary text-light-on-primary hover:bg-light-primary-container font-label text-label-md transition-colors shadow-xs"
                                >
                                    <span class="material-symbols-outlined text-[18px]">{{ acc.icon }}</span>
                                    <span>Se connecter ({{ acc.role }})</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Avantages -->
            <section id="avantages" class="py-16 lg:py-20 bg-light-surface-container-low/60 border-y border-light-outline-variant/30 scroll-mt-20">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col gap-12">
                    <div class="max-w-2xl flex flex-col gap-3">
                        <span class="font-mono text-xs uppercase tracking-wider text-light-secondary font-semibold">Qualité & Sécurité</span>
                        <h2 class="font-display text-3xl lg:text-4xl text-light-on-surface font-bold">
                            Conçue pour la confiance clinique et la sérénité des patients
                        </h2>
                        <p class="font-body text-body-lg text-light-on-surface-variant">
                            Fini les dossiers papier égarés et les longues files d'attente. Chaque acteur accède exactement aux informations dont il a besoin.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                        <article class="h-full bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-light-surface-container-low text-light-primary">
                                <span class="material-symbols-outlined text-[22px]">folder_shared</span>
                            </span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Zéro perte de dossier</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Centralisation de l'identité, des antécédents, allergies et constantes vitales dans un dossier unique.</p>
                        </article>

                        <article class="h-full bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-light-secondary-container/50 text-light-secondary">
                                <span class="material-symbols-outlined text-[22px]">event_available</span>
                            </span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Rendez-vous fluides</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Demande en ligne par le patient, confirmation ou report par le secrétariat et agenda médecin synchronisé.</p>
                        </article>

                        <article class="h-full bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-light-surface-container-low text-light-primary">
                                <span class="material-symbols-outlined text-[22px]">lock</span>
                            </span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Confidentialité stricte</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Chaque patient ne consulte que son propre dossier et chaque médecin n'accède qu'à ses patients suivis.</p>
                        </article>

                        <article class="h-full bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-4">
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-light-secondary-container/50 text-light-secondary">
                                <span class="material-symbols-outlined text-[22px]">monitoring</span>
                            </span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Traçabilité complète</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Journal d'audit inaltérable des accès et indicateurs de suivi en temps réel (conformité légale CDP Sénégal).</p>
                        </article>
                    </div>
                </div>
            </section>

            <!-- Section Principales Fonctionnalités -->
            <section id="fonctionnalites" class="py-16 lg:py-20 bg-light-surface scroll-mt-20">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col gap-12">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
                        <div class="lg:col-span-7 flex flex-col gap-3">
                            <span class="font-mono text-xs uppercase tracking-wider text-light-secondary font-semibold">Architecture Applicative</span>
                            <h2 class="font-display text-3xl lg:text-4xl text-light-on-surface font-bold">
                                Les modules essentiels du Dossier Médical Électronique
                            </h2>
                            <p class="font-body text-body-lg text-light-on-surface-variant">
                                Une interface dédiée à chaque rôle de l'établissement pour couvrir l'ensemble du parcours de soins.
                            </p>
                        </div>
                        <div class="lg:col-span-5 flex lg:justify-end">
                            <Link href="/login" class="inline-flex items-center gap-2 font-label text-label-lg text-light-primary hover:underline font-semibold">
                                <span>Explorer les espaces par rôle</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </Link>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5">
                            <img
                                src="https://lh3.googleusercontent.com/aida/ALJJyrARBAETlsmYFN1NBGyKDhN-AGC25yRH3Us3zzbZQIlW0ZGARLXpnTc2VY9-6wYyzH0a_sORux2DhPlv--sWZj2Pl3s5kPgOVNY2M0MlIYpPL3qac8132BCjUvDfZURlTSFIImu-E8XIB7NUYF-F_Jv71Ern3O5nENUc7Fvw5OYSR9YuCfzvlTJ4uY1iVg2lq9Ib0IzDQDqP51kebeUpooZRr69fqxrLJQtnBVbPGmztjN_I0jr5MAnz5oqEteU"
                                alt="Médecin consultant un dossier médical électronique sur écran"
                                class="w-full aspect-[4/3] object-cover rounded-2xl border border-light-outline-variant/40 shadow-lg"
                            >
                        </div>

                        <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="bg-light-surface-container-lowest rounded-xl p-5 border border-light-outline-variant/40 shadow-xs flex flex-col gap-2">
                                <div class="flex items-center gap-2.5 text-light-primary">
                                    <span class="material-symbols-outlined text-[22px]">calendar_clock</span>
                                    <h3 class="font-title text-title-md text-light-on-surface font-semibold">Gestion des rendez-vous</h3>
                                </div>
                                <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Demande en ligne, détection automatique de conflits d'horaires, validation par le secrétariat et agenda médecin synchronisé.</p>
                            </div>

                            <div class="bg-light-surface-container-lowest rounded-xl p-5 border border-light-outline-variant/40 shadow-xs flex flex-col gap-2">
                                <div class="flex items-center gap-2.5 text-light-secondary">
                                    <span class="material-symbols-outlined text-[22px]">clinical_notes</span>
                                    <h3 class="font-title text-title-md text-light-on-surface font-semibold">Dossier patient complet</h3>
                                </div>
                                <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Identité, antécédents, allergies, constantes vitales avec alertes cliniques (Strategy Pattern) et documents joints.</p>
                            </div>

                            <div class="bg-light-surface-container-lowest rounded-xl p-5 border border-light-outline-variant/40 shadow-xs flex flex-col gap-2">
                                <div class="flex items-center gap-2.5 text-light-primary">
                                    <span class="material-symbols-outlined text-[22px]">prescriptions</span>
                                    <h3 class="font-title text-title-md text-light-on-surface font-semibold">Consultations & Ordonnances</h3>
                                </div>
                                <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Saisie du motif, anamnèse, diagnostic, prescriptions et génération d'ordonnance imprimable conforme.</p>
                            </div>

                            <div class="bg-light-surface-container-lowest rounded-xl p-5 border border-light-outline-variant/40 shadow-xs flex flex-col gap-2">
                                <div class="flex items-center gap-2.5 text-light-secondary">
                                    <span class="material-symbols-outlined text-[22px]">admin_panel_settings</span>
                                    <h3 class="font-title text-title-md text-light-on-surface font-semibold">Administration & Audit CDP</h3>
                                </div>
                                <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Supervision des statistiques, activation des comptes utilisateurs et traçabilité inaltérable des accès (CDP Sénégal).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section Parcours Coordonné -->
            <section id="fonctionnement" class="py-16 lg:py-20 bg-light-surface-container-low/60 border-t border-light-outline-variant/30 scroll-mt-20">
                <div class="max-w-7xl mx-auto px-6 lg:px-8 flex flex-col gap-12">
                    <div class="max-w-2xl flex flex-col gap-3">
                        <span class="font-mono text-xs uppercase tracking-wider text-light-secondary font-semibold">Méthodologie</span>
                        <h2 class="font-display text-3xl lg:text-4xl text-light-on-surface font-bold">
                            Un parcours coordonné en quatre étapes
                        </h2>
                        <p class="font-body text-body-lg text-light-on-surface-variant">
                            De la demande de rendez-vous jusqu'à la consultation de l'historique par le patient.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                        <div class="bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-3">
                            <span class="font-mono text-xs font-bold text-light-primary uppercase">Étape 1 • Patient</span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Demande en ligne</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Le patient choisit sa spécialité, sélectionne son médecin et soumet sa demande de consultation.</p>
                        </div>

                        <div class="bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-3">
                            <span class="font-mono text-xs font-bold text-light-secondary uppercase">Étape 2 • Secrétaire</span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Validation du créneau</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Le secrétariat valide la demande sans conflit d'agenda et notifie la confirmation au patient.</p>
                        </div>

                        <div class="bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-3">
                            <span class="font-mono text-xs font-bold text-light-primary uppercase">Étape 3 • Médecin</span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Consultation clinique</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Le médecin évalue les alertes cliniques, enregistre le diagnostic et prescrit le traitement.</p>
                        </div>

                        <div class="bg-light-surface-container-lowest rounded-xl p-6 border border-light-outline-variant/40 shadow-xs flex flex-col gap-3">
                            <span class="font-mono text-xs font-bold text-light-secondary uppercase">Étape 4 • Suivi</span>
                            <h3 class="font-title text-title-lg text-light-on-surface font-semibold">Historique & Ordonnance</h3>
                            <p class="font-body text-body-md text-light-on-surface-variant leading-relaxed">Le patient retrouve son historique et imprime son ordonnance sécurisée en toute autonomie.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section FAQ -->
            <section id="faq" class="py-16 lg:py-20 bg-light-surface scroll-mt-20">
                <div class="max-w-4xl mx-auto px-6 lg:px-8 flex flex-col gap-10">
                    <div class="text-center flex flex-col gap-2">
                        <span class="font-mono text-xs uppercase tracking-wider text-light-secondary font-semibold">Assistance & Déontologie</span>
                        <h2 class="font-display text-3xl lg:text-4xl text-light-on-surface font-bold">Questions fréquentes</h2>
                        <p class="font-body text-body-lg text-light-on-surface-variant">Tout ce qu'il faut savoir sur la confidentialité et l'utilisation de SanteLink DME.</p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div
                            v-for="(faq, idx) in faqs"
                            :key="idx"
                            class="bg-light-surface-container-lowest rounded-xl border border-light-outline-variant/40 overflow-hidden shadow-xs"
                        >
                            <button
                                type="button"
                                @click="toggleFaq(idx)"
                                class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 hover:bg-light-surface-container-low/50 transition-colors"
                            >
                                <span class="font-title text-title-md text-light-on-surface font-semibold">{{ faq.q }}</span>
                                <span class="material-symbols-outlined text-light-primary shrink-0 transition-transform duration-200">
                                    {{ openFaq === idx ? 'expand_less' : 'expand_more' }}
                                </span>
                            </button>
                            <div
                                v-show="openFaq === idx"
                                class="px-6 pb-5 pt-1 font-body text-body-md text-light-on-surface-variant leading-relaxed border-t border-light-outline-variant/20 bg-light-surface-container-low/20"
                            >
                                {{ faq.a }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="bg-light-inverse-surface text-light-inverse-on-surface py-12 border-t border-light-outline-variant/20">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                <div class="md:col-span-5 flex flex-col gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-light-primary-container text-light-on-primary">
                            <span class="material-symbols-outlined text-[20px]">medical_services</span>
                        </span>
                        <span class="font-headline text-title-lg text-light-inverse-on-surface font-bold">SanteLink DME</span>
                    </div>
                    <p class="font-body text-body-md text-light-outline-variant max-w-sm leading-relaxed">
                        Système de Gestion de Dossier Médical Électronique — Continuité des soins, prise de rendez-vous et confidentialité stricte des données de santé.
                    </p>
                    <p class="text-xs text-light-outline-variant/70 mt-2">
                        Mémoire de Licence en Gestion des Systèmes d'Information (IG3) • Réalisé par Binetou • Encadré par M. Ahmed Ben Sidy Bouya Seye.
                    </p>
                </div>

                <div class="md:col-span-3 flex flex-col gap-2.5">
                    <span class="font-label text-label-md text-light-inverse-on-surface font-semibold">Plateforme</span>
                    <a href="#avantages" class="font-body text-body-md text-light-outline-variant hover:text-white transition-colors">Avantages</a>
                    <a href="#fonctionnalites" class="font-body text-body-md text-light-outline-variant hover:text-white transition-colors">Fonctionnalités</a>
                    <a href="#demonstration" class="font-body text-body-md text-light-outline-variant hover:text-white transition-colors">Comptes Démo</a>
                    <a href="#faq" class="font-body text-body-md text-light-outline-variant hover:text-white transition-colors">FAQ</a>
                </div>

                <div class="md:col-span-4 flex flex-col gap-2.5">
                    <span class="font-label text-label-md text-light-inverse-on-surface font-semibold">Accès & Sécurité</span>
                    <Link href="/login" class="font-body text-body-md text-light-outline-variant hover:text-white transition-colors">Connexion Utilisateur</Link>
                    <div class="font-mono text-xs text-light-outline-variant/80 pt-3 border-t border-light-outline-variant/20">
                        © 2026 SanteLink DME • Dakar, Sénégal
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
