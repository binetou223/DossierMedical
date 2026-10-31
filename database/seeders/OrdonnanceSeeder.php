<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class OrdonnanceSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les ordonnances et prescriptions.
     */
    public function run(): void
    {
        $patientAmadou = Patient::where('numero_dossier', 'PAT-2026-0001')->first();
        $patientAissatou = Patient::where('numero_dossier', 'PAT-2026-0002')->first();
        $patientMoussa = Patient::where('numero_dossier', 'PAT-2026-0003')->first();
        $patientKhadija = Patient::where('numero_dossier', 'PAT-2026-0004')->first();

        $consultationAmadou = Consultation::where('dossier_medical_id', $patientAmadou?->dossierMedical?->id)->first();
        $consultationMoussa = Consultation::where('dossier_medical_id', $patientMoussa?->dossierMedical?->id)->first();
        $consultationAissatou = Consultation::where('dossier_medical_id', $patientAissatou?->dossierMedical?->id)->first();
        $consultationKhadija = Consultation::where('dossier_medical_id', $patientKhadija?->dossierMedical?->id)->first();

        $ordonnancesData = [
            // Ordonnance 1 : Amadou Ba
            [
                'consultation' => $consultationAmadou,
                'numero_reference' => 'ORD-2026-0001',
                'date_ordonnance' => $consultationAmadou?->date_heure?->toDateString() ?? now()->toDateString(),
                'instructions_generales' => 'Prendre les comprimés aux heures régulières. Surveillance tensionnelle bi-hebdomadaire.',
                'prescriptions' => [
                    [
                        'nom_medicament' => 'Amlodipine',
                        'dosage' => '5 mg',
                        'forme' => 'Comprimé',
                        'posologie' => '1 comprimé le matin au petit-déjeuner',
                        'duree' => '3 mois renouvelable',
                        'instructions' => 'Signaler l\'apparition d\'éventuels œdèmes des chevilles.',
                    ],
                    [
                        'nom_medicament' => 'Metformine',
                        'dosage' => '1000 mg',
                        'forme' => 'Comprimé sécable',
                        'posologie' => '1 comprimé matin et soir au milieu du repas',
                        'duree' => '3 mois renouvelable',
                        'instructions' => 'Respecter scrupuleusement la prise alimentaire pendant la prise.',
                    ],
                ],
            ],

            // Ordonnance 2 : Moussa Traoré
            [
                'consultation' => $consultationMoussa,
                'numero_reference' => 'ORD-2026-0002',
                'date_ordonnance' => $consultationMoussa?->date_heure?->toDateString() ?? now()->toDateString(),
                'instructions_generales' => 'Traitement cardioprotecteur continu. Ne jamais suspendre le Kardégic sans avis spécialisé.',
                'prescriptions' => [
                    [
                        'nom_medicament' => 'Kardégic',
                        'dosage' => '75 mg',
                        'forme' => 'Poudre pour solution buvable',
                        'posologie' => '1 sachet le midi au cours du repas',
                        'duree' => '6 mois',
                        'instructions' => 'Diluer dans un demi-verre d\'eau.',
                    ],
                    [
                        'nom_medicament' => 'Atorvastatine',
                        'dosage' => '40 mg',
                        'forme' => 'Comprimé pelliculé',
                        'posologie' => '1 comprimé le soir au coucher',
                        'duree' => '6 mois',
                        'instructions' => 'Bilan lipidique et dosage des CPK si douleurs musculaires.',
                    ],
                    [
                        'nom_medicament' => 'Bisoprolol',
                        'dosage' => '5 mg',
                        'forme' => 'Comprimé sécable',
                        'posologie' => '1 comprimé le matin à jeun',
                        'duree' => '6 mois',
                        'instructions' => 'Surveiller la fréquence cardiaque au repos (> 55 bpm).',
                    ],
                    [
                        'nom_medicament' => 'Ramipril',
                        'dosage' => '5 mg',
                        'forme' => 'Gélule',
                        'posologie' => '1 gélule le matin',
                        'duree' => '6 mois',
                        'instructions' => 'Contrôle ionogramme sanguin et créatinine.',
                    ],
                ],
            ],

            // Ordonnance 3 : Aïssatou Sow
            [
                'consultation' => $consultationAissatou,
                'numero_reference' => 'ORD-2026-0003',
                'date_ordonnance' => $consultationAissatou?->date_heure?->toDateString() ?? now()->toDateString(),
                'instructions_generales' => 'Supplémentation prénatale standard pour le bon développement fœtal.',
                'prescriptions' => [
                    [
                        'nom_medicament' => 'Tardyferon B9',
                        'dosage' => '50 mg / 0.35 mg',
                        'forme' => 'Comprimé enrobé',
                        'posologie' => '1 comprimé par jour chaque matin',
                        'duree' => '3 mois',
                        'instructions' => 'À prendre de préférence avec un verre de jus d\'orange.',
                    ],
                    [
                        'nom_medicament' => 'Uvedose',
                        'dosage' => '100 000 UI',
                        'forme' => 'Ampoule buvable',
                        'posologie' => '1 ampoule en dose unique',
                        'duree' => 'Prise unique',
                        'instructions' => 'À prendre au début du 6ème mois de grossesse.',
                    ],
                ],
            ],

            // Ordonnance 4 : Khadija Sy
            [
                'consultation' => $consultationKhadija,
                'numero_reference' => 'ORD-2026-0004',
                'date_ordonnance' => $consultationKhadija?->date_heure?->toDateString() ?? now()->toDateString(),
                'instructions_generales' => 'Toujours garder l\'aérosol de secours avec soi. Rincer la bouche après la poudre.',
                'prescriptions' => [
                    [
                        'nom_medicament' => 'Seretide Diskus',
                        'dosage' => '125 µg / 25 µg',
                        'forme' => 'Poudre pour inhalation en récipient unidose',
                        'posologie' => '1 bouffée matin et soir',
                        'duree' => '3 mois',
                        'instructions' => 'Traitement de fond quotidien anti-inflammatoire et bronchodilatateur.',
                    ],
                    [
                        'nom_medicament' => 'Ventoline',
                        'dosage' => '100 µg / dose',
                        'forme' => 'Suspension pour inhalation buccale',
                        'posologie' => '1 à 2 bouffées en cas de crise ou d\'effort',
                        'duree' => 'À la demande',
                        'instructions' => 'Attendre 1 minute entre deux inhalations.',
                    ],
                ],
            ],
        ];

        foreach ($ordonnancesData as $data) {
            $consultation = $data['consultation'];
            if (! $consultation) {
                continue;
            }

            $ordonnance = $consultation->ordonnance()->updateOrCreate(
                ['consultation_id' => $consultation->id],
                [
                    'numero_reference' => $data['numero_reference'],
                    'date_ordonnance' => $data['date_ordonnance'],
                    'instructions_generales' => $data['instructions_generales'],
                ]
            );

            foreach ($data['prescriptions'] as $presc) {
                $ordonnance->prescriptions()->firstOrCreate(
                    [
                        'nom_medicament' => $presc['nom_medicament'],
                    ],
                    $presc
                );
            }
        }
    }
}
