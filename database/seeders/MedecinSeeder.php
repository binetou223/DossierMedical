<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Medecin;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class MedecinSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les profils médecins.
     */
    public function run(): void
    {
        $services = Service::all()->keyBy('code');

        $medecins = [
            [
                'email' => 'dr.diop@dossiermedical.sn',
                'service_code' => 'CARDIO',
                'matricule' => 'MED-2024-001',
                'titre' => 'Dr.',
                'specialite' => 'Cardiologie et HTA',
                'bureau' => 'Pavillon A - Bureau C-102',
                'duree_consultation_minutes' => 30,
                'jours_consultation' => ['lundi', 'mardi', 'mercredi', 'jeudi'],
            ],
            [
                'email' => 'dr.fall@dossiermedical.sn',
                'service_code' => 'PED',
                'matricule' => 'MED-2024-002',
                'titre' => 'Dr.',
                'specialite' => 'Pédiatrie générale & Néonatalogie',
                'bureau' => 'Pavillon B - Bureau P-204',
                'duree_consultation_minutes' => 20,
                'jours_consultation' => ['lundi', 'mercredi', 'vendredi'],
            ],
            [
                'email' => 'dr.diallo@dossiermedical.sn',
                'service_code' => 'MED-GEN',
                'matricule' => 'MED-2024-003',
                'titre' => 'Dr.',
                'specialite' => 'Médecine Générale & Prévention',
                'bureau' => 'Rez-de-chaussée - Bureau G-005',
                'duree_consultation_minutes' => 25,
                'jours_consultation' => ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'],
            ],
            [
                'email' => 'dr.ndiaye@dossiermedical.sn',
                'service_code' => 'GYN-OBS',
                'matricule' => 'MED-2024-004',
                'titre' => 'Dr.',
                'specialite' => 'Gynécologie & Obstétrique',
                'bureau' => 'Pavillon Maternité - Bureau GO-301',
                'duree_consultation_minutes' => 30,
                'jours_consultation' => ['mardi', 'jeudi', 'samedi'],
            ],
        ];

        foreach ($medecins as $data) {
            $user = User::where('email', $data['email'])->first();
            $service = $services->get($data['service_code']);

            if ($user && $service) {
                $user->medecin()->updateOrCreate(
                    ['matricule' => $data['matricule']],
                    [
                        'service_id' => $service->id,
                        'titre' => $data['titre'],
                        'specialite' => $data['specialite'],
                        'bureau' => $data['bureau'],
                        'duree_consultation_minutes' => $data['duree_consultation_minutes'],
                        'jours_consultation' => $data['jours_consultation'],
                    ]
                );
            }
        }
    }
}
