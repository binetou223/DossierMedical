<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Enums\AuditAction;
use App\Enums\BloodGroup;
use App\Enums\UserRole;
use App\Models\Constante;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\JournalAudit;
use App\Models\Medecin;
use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\PrescriptionMedicament;
use App\Models\RendezVous;
use App\Models\Secretaire;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder officiel du Scénario de Démonstration (Bonus Soutenance Mémoire +2 pts).
 * Prépare les 4 acteurs, le parcours de soin et les données fictives réalistes.
 */
class DemoScenarioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Services Hospitaliers
        $cardio = Service::firstOrCreate(
            ['code' => 'CARDIO'],
            ['nom' => 'Cardiologie & Hypertension', 'description' => 'Prise en charge des maladies cardiovasculaires.']
        );
        $pediatrie = Service::firstOrCreate(
            ['code' => 'PED'],
            ['nom' => 'Pédiatrie & Néonatalogie', 'description' => 'Santé et suivi de l\'enfant.']
        );
        $medInterne = Service::firstOrCreate(
            ['code' => 'MED-INT'],
            ['nom' => 'Médecine Interne & Diabétologie', 'description' => 'Maladies métaboliques et bilans généraux.']
        );

        // 2. Utilisateurs & Profils de Démonstration
        $password = Hash::make('Password@2026');

        // Compte Administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@dme.sn'],
            [
                'name' => 'Dr Ibrahima Diop (Admin)',
                'password' => $password,
                'role' => UserRole::ADMIN,
                'telephone' => '+221 77 100 00 00',
                'adresse' => 'Dakar, Fann Résidence',
                'is_active' => true,
            ]
        );

        // Compte Médecin
        $medUser = User::firstOrCreate(
            ['email' => 'medecin@dme.sn'],
            [
                'name' => 'Mamadou Ndiaye',
                'password' => $password,
                'role' => UserRole::MEDECIN,
                'telephone' => '+221 77 200 00 00',
                'adresse' => 'Dakar, Sacré-Cœur 3',
                'is_active' => true,
            ]
        );
        $medecin = Medecin::firstOrCreate(
            ['user_id' => $medUser->id],
            [
                'service_id' => $cardio->id,
                'matricule' => 'MED-CARDIO-01',
                'titre' => 'Dr',
                'specialite' => 'Cardiologue Spécialiste',
                'bureau' => 'Pavillon A, Bureau 104',
                'duree_consultation_minutes' => 30,
            ]
        );

        // Compte Secrétaire
        $secUser = User::firstOrCreate(
            ['email' => 'secretaire@dme.sn'],
            [
                'name' => 'Aïssatou Ba',
                'password' => $password,
                'role' => UserRole::SECRETAIRE,
                'telephone' => '+221 77 300 00 00',
                'adresse' => 'Dakar, Plateau',
                'is_active' => true,
            ]
        );
        Secretaire::firstOrCreate(
            ['user_id' => $secUser->id],
            [
                'matricule' => 'SEC-ACCUEIL-01',
                'poste' => 'Accueil central et admissions',
            ]
        );

        // Compte Patient Vedette (Fatou Diop)
        $patUser = User::firstOrCreate(
            ['email' => 'patient@dme.sn'],
            [
                'name' => 'Fatou Diop',
                'password' => $password,
                'role' => UserRole::PATIENT,
                'telephone' => '+221 77 400 00 00',
                'adresse' => 'Dakar, Mermoz Pyrotechnie',
                'is_active' => true,
            ]
        );
        $patient = Patient::firstOrCreate(
            ['user_id' => $patUser->id],
            [
                'numero_dossier' => 'PAT-2026-DK01',
                'date_naissance' => '1992-05-14',
                'genre' => 'F',
                'groupe_sanguin' => BloodGroup::O_POS,
                'profession' => 'Comptable',
                'contact_urgence_nom' => 'Ousmane Diop (Époux)',
                'contact_urgence_tel' => '+221 77 400 00 01',
            ]
        );

        // 3. Dossier Médical & Données Cliniques
        $dossier = DossierMedical::firstOrCreate(
            ['patient_id' => $patient->id],
            [
                'antecedents_personnels' => 'Surveillance tensionnelle, pas d\'antécédent lourd.',
                'antecedents_chirurgicaux' => 'Césarienne en 2021 sans complication.',
                'antecedents_familiaux' => 'Hypertension artérielle chez la mère, diabète paternel.',
                'allergies' => 'Pénicilline (éruption cutanée), Aspirine.',
                'traitements_chroniques' => 'Compléments magnésium et acide folique.',
                'regime_alimentaire' => 'Hypo-sodé (pauvre en sel recommandé).',
            ]
        );

        // Constantes vitales (déclenchant alertes de stratégie : pré-hypertension et surpoids)
        Constante::create([
            'dossier_medical_id' => $dossier->id,
            'prise_par_user_id' => $secUser->id,
            'date_mesure' => Carbon::now()->subDays(5),
            'poids_kg' => 74.0,
            'taille_cm' => 165,
            'tension_systolique' => 135,
            'tension_diastolique' => 88,
            'frequence_cardiaque' => 78,
            'temperature_celsius' => 37.1,
            'glycemie_g_l' => 1.05,
            'imc' => 27.2,
            'observations' => 'Prise de tension après 10 minutes de repos. Patient signalant des vertiges matinaux.',
        ]);

        // 4. Rendez-vous de Démonstration
        // RDV 1 : En attente (Pour que la secrétaire montre la confirmation en direct)
        RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'service_id' => $cardio->id,
            'date_heure_debut' => Carbon::now()->addDay()->setHour(10)->setMinute(0),
            'date_heure_fin' => Carbon::now()->addDay()->setHour(10)->setMinute(30),
            'motif' => 'Contrôle tensionnel et palpitations occasionnelles',
            'statut' => AppointmentStatus::EN_ATTENTE,
        ]);

        // RDV 2 : Confirmé (Pour que le médecin montre la réalisation de consultation et ordonnance en direct)
        $rdvConfirme = RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'service_id' => $cardio->id,
            'date_heure_debut' => Carbon::now()->subHours(2),
            'date_heure_fin' => Carbon::now()->subHours(1)->subMinutes(30),
            'motif' => 'Consultation de suivi cardiologique trimestriel',
            'statut' => AppointmentStatus::CONFIRME,
        ]);

        // RDV 3 : Terminé avec consultation et ordonnance (Pour que le patient montre son historique)
        $rdvTermine = RendezVous::create([
            'patient_id' => $patient->id,
            'medecin_id' => $medecin->id,
            'service_id' => $cardio->id,
            'date_heure_debut' => Carbon::now()->subMonths(1),
            'date_heure_fin' => Carbon::now()->subMonths(1)->addMinutes(30),
            'motif' => 'Bilan initial de cardiologie',
            'statut' => AppointmentStatus::TERMINE,
        ]);

        $consultation = Consultation::create([
            'dossier_medical_id' => $dossier->id,
            'medecin_id' => $medecin->id,
            'rendez_vous_id' => $rdvTermine->id,
            'date_heure' => Carbon::now()->subMonths(1),
            'motif_consultation' => 'Bilan initial de cardiologie',
            'histoire_maladie' => 'Patiente consultant pour épisodes de céphalées occipitales et fatigue.',
            'examen_physique' => 'Auscultation cardiaque normale. Pas de souffle carotidien. Bruits du cœur réguliers.',
            'diagnostic' => 'Pré-hypertension artérielle réactionnelle au stress professionnel.',
            'conduite_a_tenir' => 'Régime pauvre en sel, reprise de la marche active (30 min/jour), surveillance tensionnelle régulière.',
            'est_cloturee' => true,
            'cloturee_le' => Carbon::now()->subMonths(1)->addMinutes(25),
        ]);

        $ordonnance = Ordonnance::create([
            'consultation_id' => $consultation->id,
            'numero_reference' => 'ORD-202610-0042',
            'date_ordonnance' => Carbon::now()->subMonths(1)->toDateString(),
            'instructions_generales' => 'Prendre les médicaments le matin au cours du petit-déjeuner. Recontrôler la pression artérielle dans 1 mois.',
        ]);

        PrescriptionMedicament::create([
            'ordonnance_id' => $ordonnance->id,
            'nom_medicament' => 'Magnésium B6',
            'forme' => 'Comprimé',
            'dosage' => '100 mg',
            'posologie' => '2 comprimés par jour',
            'duree' => '30 jours',
            'instructions' => 'Matin et midi',
        ]);

        PrescriptionMedicament::create([
            'ordonnance_id' => $ordonnance->id,
            'nom_medicament' => 'Paracétamol',
            'forme' => 'Comprimé effervescent',
            'dosage' => '1000 mg',
            'posologie' => '1 comprimé en cas de céphalée',
            'duree' => '10 jours',
            'instructions' => 'Maximum 3 grammes par 24h, espacer de 6h',
        ]);

        // 5. Traces d'Audit Réalistes (Pour la supervision administrateur CDP)
        JournalAudit::create([
            'user_id' => $patUser->id,
            'action' => AuditAction::CONNEXION,
            'entite_type' => 'User',
            'entite_id' => $patUser->id,
            'description' => 'Connexion réussie du patient Fatou Diop',
            'ip_address' => '192.168.1.45',
            'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/130.0',
            'created_at' => Carbon::now()->subHours(4),
        ]);

        JournalAudit::create([
            'user_id' => $patUser->id,
            'action' => AuditAction::PRISE_RDV,
            'entite_type' => 'RendezVous',
            'entite_id' => 1,
            'description' => 'Demande de rendez-vous en cardiologie pour le '.Carbon::now()->addDay()->format('d/m/Y'),
            'ip_address' => '192.168.1.45',
            'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64)',
            'created_at' => Carbon::now()->subHours(3)->subMinutes(50),
        ]);

        JournalAudit::create([
            'user_id' => $medUser->id,
            'action' => AuditAction::CONSULTATION_DOSSIER,
            'entite_type' => 'DossierMedical',
            'entite_id' => $dossier->id,
            'description' => 'Consultation du dossier médical PAT-2026-DK01 avant examen',
            'ip_address' => '10.0.4.12',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64)',
            'created_at' => Carbon::now()->subHours(2),
        ]);

        JournalAudit::create([
            'user_id' => $medUser->id,
            'action' => AuditAction::ENREGISTREMENT_CONSULTATION,
            'entite_type' => 'Consultation',
            'entite_id' => $consultation->id,
            'description' => 'Saisie de la consultation et prescription de l\'ordonnance ORD-202610-0042',
            'ip_address' => '10.0.4.12',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64)',
            'created_at' => Carbon::now()->subHours(1)->subMinutes(35),
        ]);
    }
}
