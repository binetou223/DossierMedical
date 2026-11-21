<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Database\Seeders\DemoScenarioSeeder;
use Illuminate\Console\Command;

/**
 * Commande de préparation du scénario de démonstration officiel pour la soutenance (Bonus +2 pts).
 */
class SetupDemoScenarioCommand extends Command
{
    protected $signature = 'dme:demo {--fresh : Réinitialise complètement la base de données avant le seed}';

    protected $description = 'Initialise les données du scénario de démonstration officiel du mémoire DME';

    public function handle(): int
    {
        $this->info('=======================================================================');
        $this->info('  DOSSIER MÉDICAL ÉLECTRONIQUE (DME) — SCÉNARIO DE DÉMONSTRATION      ');
        $this->info('  Étudiante : Binetou (IG3 - Licence GSI) | Encadreur : M. Ahmed Seye ');
        $this->info('=======================================================================');

        if ($this->option('fresh')) {
            $this->warn('Réinitialisation des migrations de la base de données...');
            $this->call('migrate:fresh');
        } else {
            $this->call('migrate');
        }

        $this->info('Injection des données du scénario de démonstration...');
        $this->call('db:seed', ['--class' => DemoScenarioSeeder::class]);

        $this->newLine();
        $this->table(
            ['Rôle', 'Identifiant (Email)', 'Mot de passe', 'Nom & Spécialité'],
            [
                ['Administrateur', 'admin@dme.sn', 'Password@2026', 'Dr Ibrahima Diop (Supervision & Audit CDP)'],
                ['Médecin', 'medecin@dme.sn', 'Password@2026', 'Dr Mamadou Ndiaye (Cardiologue)'],
                ['Secrétaire', 'secretaire@dme.sn', 'Password@2026', 'Aïssatou Ba (Accueil & Rendez-vous)'],
                ['Patient(e)', 'patient@dme.sn', 'Password@2026', 'Fatou Diop (Dossier PAT-2026-DK01)'],
            ]
        );

        $this->newLine();
        $this->info('Déroulé du scénario de 5 à 7 minutes pour le jury :');
        $this->line(' 1. Patient : Connexion (patient@dme.sn) -> Consulte son dossier et prend un RDV.');
        $this->line(' 2. Secrétaire : Connexion (secretaire@dme.sn) -> Valide le RDV en attente.');
        $this->line(' 3. Médecin : Connexion (medecin@dme.sn) -> Ouvre le dossier, constante (alertes), consultation et ordonnance imprimable.');
        $this->line(' 4. Patient : Retrouve instantanément sa consultation et imprime son ordonnance.');
        $this->line(' 5. Administrateur : Connexion (admin@dme.sn) -> Vérifie les KPIs et la traçabilité dans le journal d\'audit CDP.');
        $this->newLine();
        $this->info('Prêt pour la démonstration ! Lancez "php artisan serve" et "npm run dev".');

        return Command::SUCCESS;
    }
}
