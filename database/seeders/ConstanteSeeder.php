<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConstanteSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les relevés de constantes vitales.
     */
    public function run(): void
    {
        $soignant = User::where('email', 'secretaire.fatou@dossiermedical.sn')->first()
            ?? User::where('email', 'admin@dossiermedical.sn')->first();

        $dossierAmadou = Patient::where('numero_dossier', 'PAT-2026-0001')->first()?->dossierMedical;
        $dossierAissatou = Patient::where('numero_dossier', 'PAT-2026-0002')->first()?->dossierMedical;
        $dossierMoussa = Patient::where('numero_dossier', 'PAT-2026-0003')->first()?->dossierMedical;
        $dossierKhadija = Patient::where('numero_dossier', 'PAT-2026-0004')->first()?->dossierMedical;
        $dossierFatimata = Patient::where('numero_dossier', 'PAT-2026-0005')->first()?->dossierMedical;

        $series = [
            // Amadou Ba (Suivi HTA & Glycémie)
            [
                'dossier' => $dossierAmadou,
                'mesures' => [
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subMonths(3)->setTime(9, 30),
                        'poids_kg' => 86.5,
                        'taille_cm' => 178,
                        'tension_systolique' => 145,
                        'tension_diastolique' => 92,
                        'frequence_cardiaque' => 78,
                        'temperature_celsius' => 36.8,
                        'glycemie_g_l' => 1.45,
                        'observations' => 'Tension légèrement élevée, adaptation du traitement recommandée.',
                    ],
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subMonths(1)->setTime(10, 15),
                        'poids_kg' => 84.8,
                        'taille_cm' => 178,
                        'tension_systolique' => 135,
                        'tension_diastolique' => 85,
                        'frequence_cardiaque' => 74,
                        'temperature_celsius' => 36.6,
                        'glycemie_g_l' => 1.28,
                        'observations' => 'Amélioration nette des chiffres tensionnels sous Amlodipine.',
                    ],
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subDays(3)->setTime(8, 45),
                        'poids_kg' => 83.9,
                        'taille_cm' => 178,
                        'tension_systolique' => 128,
                        'tension_diastolique' => 80,
                        'frequence_cardiaque' => 72,
                        'temperature_celsius' => 36.7,
                        'glycemie_g_l' => 1.15,
                        'observations' => 'Objectifs tensionnels et glycémiques atteints. Poursuite du régime.',
                    ],
                ],
            ],

            // Aïssatou Sow (Suivi prénatal)
            [
                'dossier' => $dossierAissatou,
                'mesures' => [
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subMonths(2)->setTime(11, 0),
                        'poids_kg' => 62.0,
                        'taille_cm' => 165,
                        'tension_systolique' => 115,
                        'tension_diastolique' => 72,
                        'frequence_cardiaque' => 80,
                        'temperature_celsius' => 36.9,
                        'glycemie_g_l' => 0.88,
                        'observations' => 'Constantes physiologiques normales. Prise de poids conforme.',
                    ],
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subWeeks(2)->setTime(10, 0),
                        'poids_kg' => 64.2,
                        'taille_cm' => 165,
                        'tension_systolique' => 118,
                        'tension_diastolique' => 75,
                        'frequence_cardiaque' => 82,
                        'temperature_celsius' => 37.0,
                        'glycemie_g_l' => 0.92,
                        'observations' => 'Bon état général maternel. TA stable.',
                    ],
                ],
            ],

            // Moussa Traoré (Suivi post-infarctus)
            [
                'dossier' => $dossierMoussa,
                'mesures' => [
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subMonths(2)->setTime(14, 30),
                        'poids_kg' => 76.0,
                        'taille_cm' => 172,
                        'tension_systolique' => 130,
                        'tension_diastolique' => 82,
                        'frequence_cardiaque' => 64,
                        'temperature_celsius' => 36.7,
                        'glycemie_g_l' => 1.05,
                        'observations' => 'Pouls bien régulier sous bêta-bloquants, pas de dyspnée.',
                    ],
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subDays(5)->setTime(15, 0),
                        'poids_kg' => 75.5,
                        'taille_cm' => 172,
                        'tension_systolique' => 125,
                        'tension_diastolique' => 78,
                        'frequence_cardiaque' => 62,
                        'temperature_celsius' => 36.6,
                        'glycemie_g_l' => 0.98,
                        'observations' => 'Excellente tolérance du traitement coronarien.',
                    ],
                ],
            ],

            // Khadija Sy (Suivi asthme)
            [
                'dossier' => $dossierKhadija,
                'mesures' => [
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subWeeks(3)->setTime(16, 15),
                        'poids_kg' => 58.0,
                        'taille_cm' => 168,
                        'tension_systolique' => 112,
                        'tension_diastolique' => 70,
                        'frequence_cardiaque' => 76,
                        'temperature_celsius' => 36.8,
                        'glycemie_g_l' => 0.85,
                        'observations' => 'Auscultation pulmonaire claire lors de la prise de mesure.',
                    ],
                ],
            ],

            // Fatimata Kane
            [
                'dossier' => $dossierFatimata,
                'mesures' => [
                    [
                        'prise_par_user_id' => $soignant?->id,
                        'date_mesure' => Carbon::now()->subDays(10)->setTime(9, 0),
                        'poids_kg' => 68.0,
                        'taille_cm' => 170,
                        'tension_systolique' => 120,
                        'tension_diastolique' => 78,
                        'frequence_cardiaque' => 70,
                        'temperature_celsius' => 36.7,
                        'glycemie_g_l' => 0.90,
                        'observations' => 'Bilan de contrôle sans particularité.',
                    ],
                ],
            ],
        ];

        foreach ($series as $serie) {
            $dossier = $serie['dossier'];
            if (! $dossier) {
                continue;
            }

            foreach ($serie['mesures'] as $mesure) {
                $dossier->constantes()->firstOrCreate(
                    ['date_mesure' => $mesure['date_mesure']],
                    $mesure
                );
            }
        }
    }
}
