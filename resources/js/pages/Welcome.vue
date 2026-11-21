<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Activity,
    ArrowRight,
    Calendar,
    FileText,
    HeartPulse,
    Lock,
    Pill,
    Shield,
    ShieldCheck,
    Stethoscope,
    Users,
} from '@lucide/vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const demoAccounts = [
    {
        role: 'Patient(e)',
        name: 'Fatou Diop',
        email: 'patient@dme.sn',
        password: 'Password@2026',
        desc: 'Consultation du dossier, prise de RDV, ordonnances sécurisées.',
        badge: 'Espace Patient',
        badgeClass: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300',
    },
    {
        role: 'Médecin Praticien',
        name: 'Dr Mamadou Ndiaye',
        email: 'medecin@dme.sn',
        password: 'Password@2026',
        desc: 'Examen clinique, constantes avec alertes (Strategy Pattern), ordonnances.',
        badge: 'Cardiologie',
        badgeClass: 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300',
    },
    {
        role: 'Secrétaire Médicale',
        name: 'Aïssatou Ba',
        email: 'secretaire@dme.sn',
        password: 'Password@2026',
        desc: 'Accueil, enregistrement patient, validation et calendrier des RDV.',
        badge: 'Accueil & Triage',
        badgeClass: 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300',
    },
    {
        role: 'Administrateur',
        name: 'Dr Ibrahima Diop',
        email: 'admin@dme.sn',
        password: 'Password@2026',
        desc: 'Supervision KPIs, gestion des rôles, journal d\'audit légal (CDP Sénégal).',
        badge: 'Supervision & Audit',
        badgeClass: 'bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300',
    },
];
</script>

<template>
    <div class="min-h-screen bg-gradient-to-b from-background via-muted/20 to-background text-foreground">
        <Head title="Dossier Médical Électronique (DME) — République du Sénégal" />

        <!-- Header / Navigation -->
        <header class="border-b border-border/40 backdrop-blur sticky top-0 z-50 bg-background/80">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-lg bg-primary text-primary-foreground flex items-center justify-center font-bold text-lg shadow-sm">
                        +
                    </div>
                    <div>
                        <span class="font-bold text-lg tracking-tight">Dossier Médical Électronique</span>
                        <span class="hidden sm:inline text-xs text-muted-foreground ml-2 px-2 py-0.5 rounded-full border">DME Sénégal</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <template v-if="$page.props.auth.user">
                        <Button as-child variant="default">
                            <Link href="/dashboard">
                                Accéder à mon espace
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button as-child variant="ghost">
                            <Link href="/login">Connexion</Link>
                        </Button>
                        <Button as-child class="bg-primary text-primary-foreground hover:bg-primary/90">
                            <Link href="/register">Inscription Patient</Link>
                        </Button>
                    </template>
                </div>
            </div>
        </header>

        <!-- Hero Banner avec Cadrage Académique -->
        <section class="max-w-7xl mx-auto px-6 pt-12 pb-8">
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary border border-primary/20">
                    <ShieldCheck class="h-3.5 w-3.5" />
                    Mémoire de Licence GSI — Sujet n° 5 : Étude et mise en place d'un DME
                </div>

                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight">
                    Gestion Intégrée du Dossier Médical & des Rendez-vous
                </h1>

                <p class="text-muted-foreground text-base sm:text-lg">
                    Solution Web responsive développée selon les principes <strong>SOLID</strong>, les <strong>Design Patterns</strong> et la conformité légale <strong>CDP Sénégal</strong> sur la protection des données de santé.
                </p>

                <div class="pt-2 flex flex-wrap justify-center gap-4 text-xs text-muted-foreground">
                    <span>Étudiante : <strong>Binetou</strong> (IG3)</span>
                    <span>•</span>
                    <span>Encadreur : <strong>M. Ahmed Ben Sidy Bouya Seye</strong></span>
                    <span>•</span>
                    <span>Stack : <strong>Laravel 13 • Vue.js 3 • Inertia.js • Tailwind</strong></span>
                </div>
            </div>
        </section>

        <!-- Section Démonstration Soutenance (Comptes Clé en Main) -->
        <section class="max-w-7xl mx-auto px-6 py-8">
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight">Comptes de Démonstration (Soutenance)</h2>
                        <p class="text-xs text-muted-foreground">Scénario de 5 à 7 minutes conforme au guide de cadrage de l'encadreur.</p>
                    </div>
                    <Badge variant="outline" class="font-mono text-xs">Mot de passe unique : Password@2026</Badge>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <Card v-for="acc in demoAccounts" :key="acc.email" class="hover:border-primary/50 transition-all hover:shadow-md">
                        <CardHeader class="pb-2">
                            <div class="flex items-center justify-between">
                                <Badge :class="acc.badgeClass">{{ acc.badge }}</Badge>
                                <span class="text-xs font-semibold text-muted-foreground">{{ acc.role }}</span>
                            </div>
                            <CardTitle class="text-base mt-2">{{ acc.name }}</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3 text-xs">
                            <p class="text-muted-foreground min-h-[36px]">{{ acc.desc }}</p>

                            <div class="p-2.5 rounded bg-muted/50 border font-mono space-y-1">
                                <div class="truncate text-foreground font-medium">Email : {{ acc.email }}</div>
                                <div class="text-muted-foreground">Mdp : {{ acc.password }}</div>
                            </div>

                            <Button as-child size="sm" variant="outline" class="w-full">
                                <Link :href="`/login?email=${encodeURIComponent(acc.email)}`">
                                    Se connecter en tant que {{ acc.role }}
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Scénario du Parcours de Démonstration -->
        <section class="max-w-7xl mx-auto px-6 py-8">
            <Card class="bg-card">
                <CardHeader>
                    <CardTitle class="text-lg">Déroulement du Scénario de Présentation (5 à 7 minutes)</CardTitle>
                    <CardDescription>Étapes recommandées pour la démonstration devant le jury :</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-xs">
                        <div class="p-3 rounded-lg border bg-muted/20 space-y-1.5">
                            <div class="font-bold text-primary">Étape 1 : Patient</div>
                            <p class="text-muted-foreground">Fatou Diop consulte son dossier médical et planifie une demande de rendez-vous en ligne.</p>
                        </div>
                        <div class="p-3 rounded-lg border bg-muted/20 space-y-1.5">
                            <div class="font-bold text-amber-600">Étape 2 : Secrétaire</div>
                            <p class="text-muted-foreground">Aïssatou Ba valide et confirme le rendez-vous sur l'agenda du service.</p>
                        </div>
                        <div class="p-3 rounded-lg border bg-muted/20 space-y-1.5">
                            <div class="font-bold text-blue-600">Étape 3 : Médecin</div>
                            <p class="text-muted-foreground">Dr Mamadou Ndiaye constate les alertes cliniques (Strategy Pattern), saisit l'examen et l'ordonnance.</p>
                        </div>
                        <div class="p-3 rounded-lg border bg-muted/20 space-y-1.5">
                            <div class="font-bold text-emerald-600">Étape 4 : Ordonnance</div>
                            <p class="text-muted-foreground">La patiente retrouve instantanément la consultation et télécharge son ordonnance sécurisée.</p>
                        </div>
                        <div class="p-3 rounded-lg border bg-muted/20 space-y-1.5">
                            <div class="font-bold text-purple-600">Étape 5 : Audit CDP</div>
                            <p class="text-muted-foreground">L'administrateur supervise les KPIs et atteste la traçabilité de chaque accès dans le journal d'audit.</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </section>

        <!-- Footer -->
        <footer class="border-t border-border mt-12 py-8 text-center text-xs text-muted-foreground">
            <p>Dossier Médical Électronique (DME) — Mémoire de Licence en Gestion des Systèmes d'Information (IG3).</p>
            <p class="mt-1">Conçu et développé par Binetou • Encadré par M. Ahmed Ben Sidy Bouya Seye.</p>
        </footer>
    </div>
</template>
