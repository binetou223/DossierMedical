<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les comptes utilisateurs.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('Password123!');

        $users = [
            // Administrateur
            [
                'name' => 'Super Administrateur',
                'email' => 'admin@dossiermedical.sn',
                'role' => UserRole::ADMIN,
                'telephone' => '+221 77 000 00 01',
                'adresse' => 'Direction de l\'Hôpital, Dakar, Sénégal',
                'is_active' => true,
            ],

            // Médecins
            [
                'name' => 'Dr. Cheikh Diop',
                'email' => 'dr.diop@dossiermedical.sn',
                'role' => UserRole::MEDECIN,
                'telephone' => '+221 77 123 45 67',
                'adresse' => 'Point E, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Aminata Fall',
                'email' => 'dr.fall@dossiermedical.sn',
                'role' => UserRole::MEDECIN,
                'telephone' => '+221 77 234 56 78',
                'adresse' => 'Mermoz, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Ibrahima Diallo',
                'email' => 'dr.diallo@dossiermedical.sn',
                'role' => UserRole::MEDECIN,
                'telephone' => '+221 77 345 67 89',
                'adresse' => 'Fann Résidence, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Mariama Ndiaye',
                'email' => 'dr.ndiaye@dossiermedical.sn',
                'role' => UserRole::MEDECIN,
                'telephone' => '+221 77 456 78 90',
                'adresse' => 'Almadies, Dakar, Sénégal',
                'is_active' => true,
            ],

            // Secrétaires
            [
                'name' => 'Fatou Kiné Diouf',
                'email' => 'secretaire.fatou@dossiermedical.sn',
                'role' => UserRole::SECRETAIRE,
                'telephone' => '+221 77 567 89 01',
                'adresse' => 'Plateau, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Marième Sow',
                'email' => 'secretaire.marieme@dossiermedical.sn',
                'role' => UserRole::SECRETAIRE,
                'telephone' => '+221 77 678 90 12',
                'adresse' => 'Sacré-Cœur 3, Dakar, Sénégal',
                'is_active' => true,
            ],

            // Patients
            [
                'name' => 'Amadou Ba',
                'email' => 'amadou.ba@example.com',
                'role' => UserRole::PATIENT,
                'telephone' => '+221 70 111 22 33',
                'adresse' => 'Liberté 6, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Aïssatou Sow',
                'email' => 'aissatou.sow@example.com',
                'role' => UserRole::PATIENT,
                'telephone' => '+221 76 222 33 44',
                'adresse' => 'Yoff Apecsy, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Moussa Traoré',
                'email' => 'moussa.traore@example.com',
                'role' => UserRole::PATIENT,
                'telephone' => '+221 78 333 44 55',
                'adresse' => 'Grand Yoff, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Khadija Sy',
                'email' => 'khadija.sy@example.com',
                'role' => UserRole::PATIENT,
                'telephone' => '+221 77 444 55 66',
                'adresse' => 'Nord Foire, Dakar, Sénégal',
                'is_active' => true,
            ],
            [
                'name' => 'Fatimata Kane',
                'email' => 'fatimata.kane@example.com',
                'role' => UserRole::PATIENT,
                'telephone' => '+221 70 555 66 77',
                'adresse' => 'Ouakam, Dakar, Sénégal',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => $defaultPassword,
                    'email_verified_at' => now(),
                ])
            );
        }
    }
}
