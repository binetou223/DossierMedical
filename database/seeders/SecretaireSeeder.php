<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class SecretaireSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les profils secrétaires.
     */
    public function run(): void
    {
        $secretaires = [
            [
                'email' => 'secretaire.fatou@dossiermedical.sn',
                'matricule' => 'SEC-2024-001',
                'poste' => 'Accueil central & Admissions',
            ],
            [
                'email' => 'secretaire.marieme@dossiermedical.sn',
                'matricule' => 'SEC-2024-002',
                'poste' => 'Secrétariat Consultations Externes',
            ],
        ];

        foreach ($secretaires as $data) {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                $user->secretaire()->updateOrCreate(
                    ['matricule' => $data['matricule']],
                    [
                        'poste' => $data['poste'],
                    ]
                );
            }
        }
    }
}
