<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConsultationSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les consultations médicales.
     */
    public function run(): void
    {
        $patientAmadou = Patient::where('numero_dossier', 'PAT-2026-0001')->first();
        $patientAissatou = Patient::where('numero_dossier', 'PAT-2026-0002')->first();
        $patientMoussa = Patient::where('numero_dossier', 'PAT-2026-0003')->first();
        $patientKhadija = Patient::where('numero_dossier', 'PAT-2026-0004')->first();

        $medecinDiop = Medecin::where('matricule', 'MED-2024-001')->first();
        $medecinDiallo = Medecin::where('matricule', 'MED-2024-003')->first();
        $medecinNdiaye = Medecin::where('matricule', 'MED-2024-004')->first();

        $rdvAmadou = RendezVous::where('patient_id', $patientAmadou?->id)
            ->where('medecin_id', $medecinDiop?->id)
            ->whereDate('date_heure_debut', Carbon::now()->subDays(3)->toDateString())
            ->first();

        $rdvMoussa = RendezVous::where('patient_id', $patientMoussa?->id)
            ->where('medecin_id', $medecinDiop?->id)
            ->whereDate('date_heure_debut', Carbon::now()->subDays(5)->toDateString())
            ->first();

        $rdvAissatou = RendezVous::where('patient_id', $patientAissatou?->id)
            ->where('medecin_id', $medecinNdiaye?->id)
            ->whereDate('date_heure_debut', Carbon::now()->subWeeks(2)->toDateString())
            ->first();

        $rdvKhadija = RendezVous::where('patient_id', $patientKhadija?->id)
            ->where('medecin_id', $medecinDiallo?->id)
            ->whereDate('date_heure_debut', Carbon::now()->subWeeks(3)->toDateString())
            ->first();

        $consultations = [
            // Consultation 1 : Amadou Ba (Cardio)
            [
                'dossier' => $patientAmadou?->dossierMedical,
                'medecin_id' => $medecinDiop?->id,
                'rendez_vous_id' => $rdvAmadou?->id,
                'date_heure' => Carbon::now()->subDays(3)->setTime(9, 0),
                'motif_consultation' => 'Consultation de suivi d\'hypertension et adaptation thérapeutique',
                'histoire_maladie' => 'Patient de 45 ans suivi pour HTA essentielle et DT2. Signale des céphalées matinales occasionnelles au cours du mois passé. Pas de douleurs thoraciques, pas de dyspnée d\'effort.',
                'examen_physique' => 'Constantes : TA 128/80 mmHg aux deux bras. FC 72 bpm régulier. Auscultation cardio-pulmonaire normale, pas de souffle cardiaque ni de râles crépitants. Pas d\'œdème des membres inférieurs.',
                'diagnostic' => 'Hypertension artérielle essentielle bien équilibrée sous bithérapie. Diabète de type 2 stable.',
                'conduite_a_tenir' => 'Poursuite de la bithérapie antihypertensive. Bilan rénal et microalbuminurie dans 6 mois. Maintien des règles hygiéno-diététiques.',
                'est_cloturee' => true,
                'cloturee_le' => Carbon::now()->subDays(3)->setTime(9, 30),
            ],

            // Consultation 2 : Moussa Traoré (Cardio)
            [
                'dossier' => $patientMoussa?->dossierMedical,
                'medecin_id' => $medecinDiop?->id,
                'rendez_vous_id' => $rdvMoussa?->id,
                'date_heure' => Carbon::now()->subDays(5)->setTime(14, 30),
                'motif_consultation' => 'Contrôle cardiologique annuel post-angioplastie',
                'histoire_maladie' => 'Patient de 62 ans, stent coronarien posé en 2017. Très bonne tolérance à l\'effort physique modéré (marche 45 min quotidienne). Aucun angor d\'effort résiduel.',
                'examen_physique' => 'TA 125/78 mmHg, FC 62 bpm. Bruits du cœur réguliers sans souffle surajouté. Pouls périphériques bien perçus. ECG per-consultation : rythme sinusal régulier, pas d\'ischémie aiguë.',
                'diagnostic' => 'Insuffisance coronarienne chronique stable sous quadruple thérapie de prévention secondaire.',
                'conduite_a_tenir' => 'Reconduction du traitement de fond (Kardégic, Atorvastatine, Bêta-bloquant, IEC). Épreuve d\'effort de contrôle programmée dans 6 mois.',
                'est_cloturee' => true,
                'cloturee_le' => Carbon::now()->subDays(5)->setTime(15, 0),
            ],

            // Consultation 3 : Aïssatou Sow (Gynéco)
            [
                'dossier' => $patientAissatou?->dossierMedical,
                'medecin_id' => $medecinNdiaye?->id,
                'rendez_vous_id' => $rdvAissatou?->id,
                'date_heure' => Carbon::now()->subWeeks(2)->setTime(10, 0),
                'motif_consultation' => 'Consultation prénatale du 5ème mois et échographie de suivi',
                'histoire_maladie' => 'Primigeste de 29 ans, 22 SA. Mouvements fœtaux actifs bien perçus quotidiennement. Absence de métrorragies, absence de contractions utérines.',
                'examen_physique' => 'TA 118/75 mmHg. Hauteur utérine 20 cm conforme au terme. Bruits du cœur fœtaux bien audibles et réguliers (144 bpm). Absence d\'œdème des membres inférieurs.',
                'diagnostic' => 'Grossesse monofoetale évolutive du 2ème trimestre normale sans facteur de risque surajouté.',
                'conduite_a_tenir' => 'Poursuite de la supplémentation en fer et acide folique. Consultation du 6ème mois programmée. Prise de sang mensuelle (toxoplasmose séronégative).',
                'est_cloturee' => true,
                'cloturee_le' => Carbon::now()->subWeeks(2)->setTime(10, 30),
            ],

            // Consultation 4 : Khadija Sy (Médecine Générale)
            [
                'dossier' => $patientKhadija?->dossierMedical,
                'medecin_id' => $medecinDiallo?->id,
                'rendez_vous_id' => $rdvKhadija?->id,
                'date_heure' => Carbon::now()->subWeeks(3)->setTime(16, 0),
                'motif_consultation' => 'Bilan et renouvellement du traitement de fond pour asthme',
                'histoire_maladie' => 'Patiente de 22 ans, asthme atopique connu. Décrit une gêne respiratoire nocturne occasionnelle lors des épisodes de vent d\'est (harmattan) et poussière.',
                'examen_physique' => 'Auscultation pulmonaire symétrique, absence de râles sibilants au repos. Peak Flow mesuré à 420 L/min (conforme à la valeur théorique).',
                'diagnostic' => 'Asthme modéré contrôlé, avec exacerbations mineures liées aux allergènes aéroportés.',
                'conduite_a_tenir' => 'Renouvellement Seretide et Ventoline de secours. Rappel des mesures d\'éviction des acariens et de la poussière.',
                'est_cloturee' => true,
                'cloturee_le' => Carbon::now()->subWeeks(3)->setTime(16, 25),
            ],
        ];

        foreach ($consultations as $item) {
            $dossier = $item['dossier'];
            if (! $dossier) {
                continue;
            }

            $dossier->consultations()->firstOrCreate(
                [
                    'medecin_id' => $item['medecin_id'],
                    'date_heure' => $item['date_heure'],
                ],
                [
                    'rendez_vous_id' => $item['rendez_vous_id'],
                    'motif_consultation' => $item['motif_consultation'],
                    'histoire_maladie' => $item['histoire_maladie'],
                    'examen_physique' => $item['examen_physique'],
                    'diagnostic' => $item['diagnostic'],
                    'conduite_a_tenir' => $item['conduite_a_tenir'],
                    'est_cloturee' => $item['est_cloturee'],
                    'cloturee_le' => $item['cloturee_le'],
                ]
            );
        }
    }
}
