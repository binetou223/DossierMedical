<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RendezVousSeeder extends Seeder
{
    /**
     * Exécuter le seeder pour les rendez-vous.
     */
    public function run(): void
    {
        $patientAmadou = Patient::where('numero_dossier', 'PAT-2026-0001')->first();
        $patientAissatou = Patient::where('numero_dossier', 'PAT-2026-0002')->first();
        $patientMoussa = Patient::where('numero_dossier', 'PAT-2026-0003')->first();
        $patientKhadija = Patient::where('numero_dossier', 'PAT-2026-0004')->first();
        $patientFatimata = Patient::where('numero_dossier', 'PAT-2026-0005')->first();

        $medecinDiop = Medecin::where('matricule', 'MED-2024-001')->first();
        $medecinFall = Medecin::where('matricule', 'MED-2024-002')->first();
        $medecinDiallo = Medecin::where('matricule', 'MED-2024-003')->first();
        $medecinNdiaye = Medecin::where('matricule', 'MED-2024-004')->first();

        $serviceCardio = Service::where('code', 'CARDIO')->first();
        $serviceGen = Service::where('code', 'MED-GEN')->first();
        $serviceGyn = Service::where('code', 'GYN-OBS')->first();
        $servicePed = Service::where('code', 'PED')->first();

        $rendezVousList = [
            // RDV 1 : Terminé - Amadou Ba (Cardio)
            [
                'patient_id' => $patientAmadou?->id,
                'medecin_id' => $medecinDiop?->id,
                'service_id' => $serviceCardio?->id,
                'date_heure_debut' => Carbon::now()->subDays(3)->setTime(9, 0),
                'date_heure_fin' => Carbon::now()->subDays(3)->setTime(9, 30),
                'motif' => 'Consultation de suivi d\'hypertension et adaptation thérapeutique',
                'statut' => AppointmentStatus::TERMINE,
                'notes_internes' => 'Patient reçu avec carnet d\'auto-mesures tensionnelles.',
            ],

            // RDV 2 : Terminé - Moussa Traoré (Cardio)
            [
                'patient_id' => $patientMoussa?->id,
                'medecin_id' => $medecinDiop?->id,
                'service_id' => $serviceCardio?->id,
                'date_heure_debut' => Carbon::now()->subDays(5)->setTime(14, 30),
                'date_heure_fin' => Carbon::now()->subDays(5)->setTime(15, 0),
                'motif' => 'Contrôle cardiologique annuel post-angioplastie',
                'statut' => AppointmentStatus::TERMINE,
                'notes_internes' => 'ECG de repos réalisé en début de consultation.',
            ],

            // RDV 3 : Terminé - Aïssatou Sow (Gynéco)
            [
                'patient_id' => $patientAissatou?->id,
                'medecin_id' => $medecinNdiaye?->id,
                'service_id' => $serviceGyn?->id,
                'date_heure_debut' => Carbon::now()->subWeeks(2)->setTime(10, 0),
                'date_heure_fin' => Carbon::now()->subWeeks(2)->setTime(10, 30),
                'motif' => 'Consultation prénatale du 5ème mois et échographie de suivi',
                'statut' => AppointmentStatus::TERMINE,
                'notes_internes' => 'Échographie obstétricale morphologique conforme.',
            ],

            // RDV 4 : Terminé - Khadija Sy (Médecine Générale)
            [
                'patient_id' => $patientKhadija?->id,
                'medecin_id' => $medecinDiallo?->id,
                'service_id' => $serviceGen?->id,
                'date_heure_debut' => Carbon::now()->subWeeks(3)->setTime(16, 0),
                'date_heure_fin' => Carbon::now()->subWeeks(3)->setTime(16, 25),
                'motif' => 'Bilan et renouvellement du traitement de fond pour asthme',
                'statut' => AppointmentStatus::TERMINE,
                'notes_internes' => 'Vérification du Peak Flow et de la technique d\'inhalation.',
            ],

            // RDV 5 : Annulé - Fatimata Kane (Médecine Générale)
            [
                'patient_id' => $patientFatimata?->id,
                'medecin_id' => $medecinDiallo?->id,
                'service_id' => $serviceGen?->id,
                'date_heure_debut' => Carbon::now()->subWeeks(1)->setTime(11, 0),
                'date_heure_fin' => Carbon::now()->subWeeks(1)->setTime(11, 25),
                'motif' => 'Bilan de santé annuel et migraines récurrentes',
                'statut' => AppointmentStatus::ANNULE,
                'motif_annulation' => 'Empêchement professionnel imprévu signalé par la patiente.',
                'notes_internes' => 'Report proposé pour la semaine suivante.',
            ],

            // RDV 6 : À venir (Confirmé) - Amadou Ba (Médecine Générale)
            [
                'patient_id' => $patientAmadou?->id,
                'medecin_id' => $medecinDiallo?->id,
                'service_id' => $serviceGen?->id,
                'date_heure_debut' => Carbon::now()->addDays(2)->setTime(9, 30),
                'date_heure_fin' => Carbon::now()->addDays(2)->setTime(9, 55),
                'motif' => 'Contrôle du bilan biologique sanguin et réévaluation glycémique',
                'statut' => AppointmentStatus::CONFIRME,
                'notes_internes' => 'Résultats d\'HbA1c attendus au laboratoire.',
            ],

            // RDV 7 : À venir (Confirmé) - Aïssatou Sow (Gynéco)
            [
                'patient_id' => $patientAissatou?->id,
                'medecin_id' => $medecinNdiaye?->id,
                'service_id' => $serviceGyn?->id,
                'date_heure_debut' => Carbon::now()->addDays(4)->setTime(11, 0),
                'date_heure_fin' => Carbon::now()->addDays(4)->setTime(11, 30),
                'motif' => 'Visite prénatale du 6ème mois',
                'statut' => AppointmentStatus::CONFIRME,
                'notes_internes' => 'Penser à vérifier la sérologie toxoplasmose.',
            ],

            // RDV 8 : À venir (En attente) - Fatimata Kane (Cardiologie)
            [
                'patient_id' => $patientFatimata?->id,
                'medecin_id' => $medecinDiop?->id,
                'service_id' => $serviceCardio?->id,
                'date_heure_debut' => Carbon::now()->addDays(7)->setTime(14, 0),
                'date_heure_fin' => Carbon::now()->addDays(7)->setTime(14, 30),
                'motif' => 'Avis spécialisé pour épisodes de palpitations et fatigue',
                'statut' => AppointmentStatus::EN_ATTENTE,
                'notes_internes' => 'Demande de RDV en ligne via le portail patient.',
            ],

            // RDV 9 : À venir (En attente) - Khadija Sy (Pédiatrie)
            [
                'patient_id' => $patientKhadija?->id,
                'medecin_id' => $medecinFall?->id,
                'service_id' => $servicePed?->id,
                'date_heure_debut' => Carbon::now()->addDays(10)->setTime(10, 30),
                'date_heure_fin' => Carbon::now()->addDays(10)->setTime(10, 50),
                'motif' => 'Conseil et réévaluation des allergies saisonnières',
                'statut' => AppointmentStatus::EN_ATTENTE,
                'notes_internes' => 'Dossier à transférer au Dr. Fall.',
            ],
        ];

        foreach ($rendezVousList as $data) {
            if ($data['patient_id'] && $data['medecin_id'] && $data['service_id']) {
                RendezVous::firstOrCreate(
                    [
                        'patient_id' => $data['patient_id'],
                        'medecin_id' => $data['medecin_id'],
                        'date_heure_debut' => $data['date_heure_debut'],
                    ],
                    $data
                );
            }
        }
    }
}
