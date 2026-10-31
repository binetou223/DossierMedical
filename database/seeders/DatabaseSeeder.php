<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Exécuter l'ensemble des seeders de l'application.
     */
    public function run(): void
    {
        $this->command->info('Démarrage de l\'amorçage Eloquent pour Dossier Médical...');

        $this->call([
            ServiceSeeder::class,
            UserSeeder::class,
            MedecinSeeder::class,
            SecretaireSeeder::class,
            PatientSeeder::class,
            DossierMedicalSeeder::class,
            ConstanteSeeder::class,
            RendezVousSeeder::class,
            ConsultationSeeder::class,
            OrdonnanceSeeder::class,
            DocumentMedicalSeeder::class,
            JournalAuditSeeder::class,
        ]);

        $this->command->info('Amorçage Eloquent terminé avec succès !');
    }
}
