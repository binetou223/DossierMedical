<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les services médicaux.
     */
    public function run(): void
    {
        $services = [
            [
                'code' => 'MED-GEN',
                'nom' => 'Médecine Générale',
                'description' => 'Prise en charge globale des adultes et enfants, prévention, diagnostic et orientation.',
                'icon' => 'stethoscope',
                'couleur' => '#2563eb',
                'is_active' => true,
            ],
            [
                'code' => 'CARDIO',
                'nom' => 'Cardiologie',
                'description' => 'Prévention, diagnostic et traitement des maladies cardiovasculaires et de l\'hypertension artérielle.',
                'icon' => 'heart-pulse',
                'couleur' => '#dc2626',
                'is_active' => true,
            ],
            [
                'code' => 'PED',
                'nom' => 'Pédiatrie',
                'description' => 'Santé, croissance et développement de l\'enfant, du nourrisson à l\'adolescent.',
                'icon' => 'baby',
                'couleur' => '#16a34a',
                'is_active' => true,
            ],
            [
                'code' => 'GYN-OBS',
                'nom' => 'Gynécologie & Obstétrique',
                'description' => 'Suivi gynécologique régulier, suivi de grossesse, accouchement et santé de la femme.',
                'icon' => 'user-check',
                'couleur' => '#9333ea',
                'is_active' => true,
            ],
            [
                'code' => 'DERMATO',
                'nom' => 'Dermatologie',
                'description' => 'Diagnostic et traitement des affections de la peau, des muqueuses, des ongles et des cheveux.',
                'icon' => 'sparkles',
                'couleur' => '#ea580c',
                'is_active' => true,
            ],
            [
                'code' => 'OPHTA',
                'nom' => 'Ophtalmologie',
                'description' => 'Examen visuel, traitement des troubles de la vision et des pathologies oculaires.',
                'icon' => 'eye',
                'couleur' => '#0284c7',
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['code' => $serviceData['code']],
                $serviceData
            );
        }
    }
}
