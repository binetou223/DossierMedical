<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BloodGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les profils patients.
     */
    public function run(): void
    {
        $patients = [
            [
                'email' => 'amadou.ba@example.com',
                'numero_dossier' => 'PAT-2026-0001',
                'date_naissance' => '1979-05-14',
                'genre' => 'M',
                'groupe_sanguin' => BloodGroup::O_POS,
                'profession' => 'Enseignant-Chercheur',
                'contact_urgence_nom' => 'Mariama Ba (Épouse)',
                'contact_urgence_tel' => '+221 77 987 65 43',
            ],
            [
                'email' => 'aissatou.sow@example.com',
                'numero_dossier' => 'PAT-2026-0002',
                'date_naissance' => '1995-11-20',
                'genre' => 'F',
                'groupe_sanguin' => BloodGroup::A_POS,
                'profession' => 'Ingénieure Télécoms',
                'contact_urgence_nom' => 'Abdoulaye Sow (Frère)',
                'contact_urgence_tel' => '+221 77 876 54 32',
            ],
            [
                'email' => 'moussa.traore@example.com',
                'numero_dossier' => 'PAT-2026-0003',
                'date_naissance' => '1962-03-08',
                'genre' => 'M',
                'groupe_sanguin' => BloodGroup::B_POS,
                'profession' => 'Comptable retraité',
                'contact_urgence_nom' => 'Ousmane Traoré (Fils)',
                'contact_urgence_tel' => '+221 76 765 43 21',
            ],
            [
                'email' => 'khadija.sy@example.com',
                'numero_dossier' => 'PAT-2026-0004',
                'date_naissance' => '2002-07-25',
                'genre' => 'F',
                'groupe_sanguin' => BloodGroup::AB_POS,
                'profession' => 'Étudiante en Master',
                'contact_urgence_nom' => 'Aminata Sy (Mère)',
                'contact_urgence_tel' => '+221 77 654 32 10',
            ],
            [
                'email' => 'fatimata.kane@example.com',
                'numero_dossier' => 'PAT-2026-0005',
                'date_naissance' => '1986-09-12',
                'genre' => 'F',
                'groupe_sanguin' => BloodGroup::O_NEG,
                'profession' => 'Directrice Commerciale',
                'contact_urgence_nom' => 'Babacar Kane (Époux)',
                'contact_urgence_tel' => '+221 70 543 21 09',
            ],
        ];

        foreach ($patients as $data) {
            $user = User::where('email', $data['email'])->first();

            if ($user) {
                $user->patient()->updateOrCreate(
                    ['numero_dossier' => $data['numero_dossier']],
                    [
                        'date_naissance' => $data['date_naissance'],
                        'genre' => $data['genre'],
                        'groupe_sanguin' => $data['groupe_sanguin'],
                        'profession' => $data['profession'],
                        'contact_urgence_nom' => $data['contact_urgence_nom'],
                        'contact_urgence_tel' => $data['contact_urgence_tel'],
                    ]
                );
            }
        }
    }
}
