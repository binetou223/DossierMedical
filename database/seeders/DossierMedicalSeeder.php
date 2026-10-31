<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class DossierMedicalSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les dossiers médicaux.
     */
    public function run(): void
    {
        $dossiers = [
            'PAT-2026-0001' => [
                'antecedents_personnels' => 'Hypertension artérielle essentielle diagnostiquée en 2019, Diabète de type 2 équilibré sous antidiabétiques oraux.',
                'antecedents_chirurgicaux' => 'Appendicectomie en 2005 sans complication opératoire.',
                'antecedents_familiaux' => 'Père décédé d\'AVC à 68 ans, Mère diabétique de type 2 sous insuline.',
                'allergies' => 'Allergie sévère à la Pénicilline (choc urticarien).',
                'traitements_chroniques' => 'Metformine 1000 mg (1 comp matin et soir), Amlodipine 5 mg (1 comp le matin).',
                'regime_alimentaire' => 'Régime hyposodé, pauvre en sucres simples et graisses saturées.',
                'notes_confidentielles' => 'Patient très rigoureux et observant. Bilan annuel rénal et cardiovasculaire à jour.',
            ],
            'PAT-2026-0002' => [
                'antecedents_personnels' => 'Aucun antécédent médical notable. Suivi de première grossesse en cours.',
                'antecedents_chirurgicaux' => 'Aucun antécédent chirurgical.',
                'antecedents_familiaux' => 'Pas d\'antécédent pathologique familial répertorié.',
                'allergies' => 'Aucune allergie médicamenteuse ou alimentaire connue.',
                'traitements_chroniques' => 'Acide folique 0.4 mg/j, Supplémentation Tardyferon B9 et Vitamine D.',
                'regime_alimentaire' => 'Alimentation saine et équilibrée, éviction des produits laitiers non pasteurisés.',
                'notes_confidentielles' => 'Grossesse évolutive normale. Suivi prénatal régulier.',
            ],
            'PAT-2026-0003' => [
                'antecedents_personnels' => 'Infarctus du myocarde en 2017 avec revascularisation par stent. Insuffisance coronarienne stable, dyslipidémie.',
                'antecedents_chirurgicaux' => 'Angioplastie coronaire transluminale (2017).',
                'antecedents_familiaux' => 'Cardiopathie ischémique chez les deux frères aînés.',
                'allergies' => 'Intolérance digestive majeure aux Anti-Inflammatoires Non Stéroïdiens (AINS).',
                'traitements_chroniques' => 'Kardégic 75 mg/j, Atorvastatine 40 mg/j, Bisoprolol 5 mg/j, Ramipril 5 mg/j.',
                'regime_alimentaire' => 'Régime méditerranéen hypocholestérolémiant strict et sans sel ajouté.',
                'notes_confidentielles' => 'Contrôle cardiologique et ECG semestriels obligatoires.',
            ],
            'PAT-2026-0004' => [
                'antecedents_personnels' => 'Asthme bronchique modéré persistant depuis l\'âge de 8 ans.',
                'antecedents_chirurgicaux' => 'Amygdalectomie bilatérale à l\'âge de 7 ans.',
                'antecedents_familiaux' => 'Terrain atopique familial (rhinite allergique maternelle).',
                'allergies' => 'Acariens de poussière, pollens de graminées, poils de chat.',
                'traitements_chroniques' => 'Ventoline 100 µg en cas de crise, Seretide 125/25 µg (2 bouffées matin et soir en période hivernale).',
                'regime_alimentaire' => 'Normal.',
                'notes_confidentielles' => 'Éducation thérapeutique d\'auto-mesure du débit de pointe (Peak Flow) validée.',
            ],
            'PAT-2026-0005' => [
                'antecedents_personnels' => 'Migraines ophtalmiques cataméniales récurrentes, gastrites occasionnelles.',
                'antecedents_chirurgicaux' => 'Césarienne sous rachianesthésie en 2015.',
                'antecedents_familiaux' => 'Hypertension artérielle essentielle chez la mère.',
                'allergies' => 'Allergie cutanée aux sulfamides antibactériens.',
                'traitements_chroniques' => 'Sumatriptan 50 mg à la demande en début de céphalée migraineuse.',
                'regime_alimentaire' => 'Normal, réduction des excitants (café, thé fort).',
                'notes_confidentielles' => 'Bilan biologique et métabolique récent satisfaisant.',
            ],
        ];

        foreach ($dossiers as $numeroDossier => $dossierData) {
            $patient = Patient::where('numero_dossier', $numeroDossier)->first();

            if ($patient) {
                $patient->dossierMedical()->updateOrCreate(
                    ['patient_id' => $patient->id],
                    $dossierData
                );
            }
        }
    }
}
