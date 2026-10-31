<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AuditAction;
use App\Models\JournalAudit;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JournalAuditSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les traces d'audit.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@dossiermedical.sn')->first();
        $medecinDiop = User::where('email', 'dr.diop@dossiermedical.sn')->first();
        $medecinDiallo = User::where('email', 'dr.diallo@dossiermedical.sn')->first();
        $secretaireFatou = User::where('email', 'secretaire.fatou@dossiermedical.sn')->first();
        $patientAmadou = Patient::where('numero_dossier', 'PAT-2026-0001')->first();

        $auditLogs = [
            [
                'user_id' => $admin?->id,
                'action' => AuditAction::CONNEXION,
                'entite_type' => 'User',
                'entite_id' => $admin?->id,
                'description' => 'Connexion réussie au panneau d\'administration système',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'details_json' => ['auth_method' => 'password'],
                'created_at' => Carbon::now()->subDays(5)->setTime(8, 0),
            ],
            [
                'user_id' => $secretaireFatou?->id,
                'action' => AuditAction::PRISE_RDV,
                'entite_type' => 'RendezVous',
                'entite_id' => 1,
                'description' => 'Création d\'un rendez-vous cardiologique pour le patient Amadou Ba',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64)',
                'details_json' => ['patient_id' => $patientAmadou?->id, 'specialite' => 'Cardiologie'],
                'created_at' => Carbon::now()->subDays(4)->setTime(10, 15),
            ],
            [
                'user_id' => $medecinDiop?->id,
                'action' => AuditAction::CONSULTATION_DOSSIER,
                'entite_type' => 'DossierMedical',
                'entite_id' => $patientAmadou?->dossierMedical?->id,
                'description' => 'Ouverture et consultation du dossier médical du patient Amadou Ba',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64)',
                'details_json' => ['motif' => 'Consultation de suivi HTA'],
                'created_at' => Carbon::now()->subDays(3)->setTime(9, 2),
            ],
            [
                'user_id' => $medecinDiop?->id,
                'action' => AuditAction::ENREGISTREMENT_CONSULTATION,
                'entite_type' => 'Consultation',
                'entite_id' => 1,
                'description' => 'Saisie des notes cliniques de consultation pour Amadou Ba',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64)',
                'details_json' => ['diagnostic' => 'HTA équilibrée'],
                'created_at' => Carbon::now()->subDays(3)->setTime(9, 25),
            ],
            [
                'user_id' => $medecinDiop?->id,
                'action' => AuditAction::CLOTURE_CONSULTATION,
                'entite_type' => 'Consultation',
                'entite_id' => 1,
                'description' => 'Validation et clôture définitive de la consultation #1',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64)',
                'details_json' => ['statut' => 'cloturee'],
                'created_at' => Carbon::now()->subDays(3)->setTime(9, 30),
            ],
            [
                'user_id' => $medecinDiop?->id,
                'action' => AuditAction::IMPRESSION_ORDONNANCE,
                'entite_type' => 'Ordonnance',
                'entite_id' => 1,
                'description' => 'Génération et impression de l\'ordonnance ORD-2026-0001',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64)',
                'details_json' => ['reference' => 'ORD-2026-0001'],
                'created_at' => Carbon::now()->subDays(3)->setTime(9, 31),
            ],
            [
                'user_id' => $medecinDiallo?->id,
                'action' => AuditAction::CONNEXION,
                'entite_type' => 'User',
                'entite_id' => $medecinDiallo?->id,
                'description' => 'Connexion utilisateur médecin Dr. Ibrahima Diallo',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64)',
                'details_json' => ['auth_method' => 'password'],
                'created_at' => Carbon::now()->subDays(1)->setTime(8, 30),
            ],
        ];

        foreach ($auditLogs as $log) {
            JournalAudit::firstOrCreate(
                [
                    'user_id' => $log['user_id'],
                    'action' => $log['action'],
                    'description' => $log['description'],
                    'created_at' => $log['created_at'],
                ],
                $log
            );
        }
    }
}
