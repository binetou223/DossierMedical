<?php

declare(strict_types=1);

use App\Enums\AppointmentStatus;
use App\Enums\AuditAction;
use App\Enums\BloodGroup;
use App\Enums\UserRole;
use App\Models\Constante;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use App\Models\User;
use App\Repositories\Contracts\ConsultationRepositoryInterface;
use App\Repositories\Contracts\DossierMedicalRepositoryInterface;
use App\Repositories\Contracts\JournalAuditRepositoryInterface;
use App\Repositories\Contracts\MedecinRepositoryInterface;
use App\Repositories\Contracts\PatientRepositoryInterface;
use App\Repositories\Contracts\RendezVousRepositoryInterface;
use App\Repositories\Eloquent\EloquentConsultationRepository;
use App\Repositories\Eloquent\EloquentDossierMedicalRepository;
use App\Repositories\Eloquent\EloquentJournalAuditRepository;
use App\Repositories\Eloquent\EloquentMedecinRepository;
use App\Repositories\Eloquent\EloquentPatientRepository;
use App\Repositories\Eloquent\EloquentRendezVousRepository;
use Carbon\Carbon;

test('le conteneur de services Laravel injecte les bonnes implementations de repositories (DIP)', function () {
    expect(app(PatientRepositoryInterface::class))->toBeInstanceOf(EloquentPatientRepository::class)
        ->and(app(RendezVousRepositoryInterface::class))->toBeInstanceOf(EloquentRendezVousRepository::class)
        ->and(app(DossierMedicalRepositoryInterface::class))->toBeInstanceOf(EloquentDossierMedicalRepository::class)
        ->and(app(ConsultationRepositoryInterface::class))->toBeInstanceOf(EloquentConsultationRepository::class)
        ->and(app(JournalAuditRepositoryInterface::class))->toBeInstanceOf(EloquentJournalAuditRepository::class)
        ->and(app(MedecinRepositoryInterface::class))->toBeInstanceOf(EloquentMedecinRepository::class);
});

test('PatientRepository::createWithUser cree le compte, le profil et le dossier medical', function () {
    /** @var PatientRepositoryInterface $repo */
    $repo = app(PatientRepositoryInterface::class);

    $patient = $repo->createWithUser(
        userData: [
            'name' => 'Binetou Sow',
            'email' => 'binetou.sow@example.com',
            'password' => bcrypt('Secur1ty!2026'),
            'telephone' => '+221775556677',
            'adresse' => 'Dakar, Point E',
        ],
        patientData: [
            'date_naissance' => '1998-07-22',
            'genre' => 'F',
            'groupe_sanguin' => BloodGroup::A_POS,
            'profession' => 'Ingenieure',
            'contact_urgence_nom' => 'Moussa Sow',
            'contact_urgence_tel' => '+221771112233',
        ]
    );

    expect($patient)->toBeInstanceOf(Patient::class)
        ->and($patient->user->email)->toBe('binetou.sow@example.com')
        ->and($patient->user->role)->toBe(UserRole::PATIENT)
        ->and($patient->groupe_sanguin)->toBe(BloodGroup::A_POS)
        ->and($patient->dossierMedical)->not->toBeNull()
        ->and($patient->numero_dossier)->toStartWith('PAT-');

    // Test de recherche par numero de dossier
    $found = $repo->findByNumeroDossier($patient->numero_dossier);
    expect($found)->not->toBeNull()
        ->and($found?->id)->toBe($patient->id);

    // Test de recherche multicritere
    $results = $repo->search('Binetou');
    expect($results->total())->toBeGreaterThanOrEqual(1);
});

test('RendezVousRepository detecte les conflits de creneaux horaires et gere les statuts', function () {
    $service = Service::create([
        'nom' => 'Cardiologie',
        'code' => 'CARDIO',
        'description' => 'Service de cardiologie',
    ]);

    $medecinUser = User::create([
        'name' => 'Dr Ousmane Ba',
        'email' => 'ousmane.ba@example.com',
        'password' => bcrypt('password'),
        'role' => UserRole::MEDECIN,
    ]);

    $medecin = Medecin::create([
        'user_id' => $medecinUser->id,
        'service_id' => $service->id,
        'matricule' => 'MED-0099',
        'specialite' => 'Cardiologue',
        'titre' => 'Dr',
        'bureau' => 'B-102',
    ]);

    $patientUser = User::create([
        'name' => 'Amadou Fall',
        'email' => 'amadou.fall@example.com',
        'password' => bcrypt('password'),
        'role' => UserRole::PATIENT,
    ]);

    $patient = Patient::create([
        'user_id' => $patientUser->id,
        'numero_dossier' => 'PAT-2026-0001',
        'genre' => 'M',
    ]);

    /** @var RendezVousRepositoryInterface $repo */
    $repo = app(RendezVousRepositoryInterface::class);

    $debut = Carbon::now()->addDays(2)->setHour(10)->setMinute(0)->setSecond(0);
    $fin = (clone $debut)->addMinutes(30);

    $rdv = $repo->create([
        'patient_id' => $patient->id,
        'medecin_id' => $medecin->id,
        'service_id' => $service->id,
        'date_heure_debut' => $debut,
        'date_heure_fin' => $fin,
        'motif' => 'Douleurs thoraciques',
        'statut' => AppointmentStatus::EN_ATTENTE,
    ]);

    expect($rdv)->toBeInstanceOf(RendezVous::class)
        ->and($rdv->statut)->toBe(AppointmentStatus::EN_ATTENTE);

    // Verifier le conflit : un creneau chevauchant 10h15 - 10h45
    $conflitDebut = (clone $debut)->addMinutes(15);
    $conflitFin = (clone $conflitDebut)->addMinutes(30);

    expect($repo->hasConflict($medecin->id, $conflitDebut, $conflitFin))->toBeTrue();

    // Verifier aucun conflit pour un creneau apres
    $apresDebut = (clone $fin)->addMinutes(10);
    $apresFin = (clone $apresDebut)->addMinutes(30);
    expect($repo->hasConflict($medecin->id, $apresDebut, $apresFin))->toBeFalse();

    // Mise a jour du statut vers CONFIRME
    $confirmedRdv = $repo->updateStatus($rdv->id, AppointmentStatus::CONFIRME);
    expect($confirmedRdv->statut)->toBe(AppointmentStatus::CONFIRME);

    // Annulation avec motif
    $cancelledRdv = $repo->updateStatus($rdv->id, AppointmentStatus::ANNULE, 'Empechement patient');
    expect($cancelledRdv->statut)->toBe(AppointmentStatus::ANNULE)
        ->and($cancelledRdv->motif_annulation)->toBe('Empechement patient');

    // Un RDV annule ne doit plus causer de conflit
    expect($repo->hasConflict($medecin->id, $conflitDebut, $conflitFin))->toBeFalse();
});

test('DossierMedicalRepository gere les constantes et les documents joints', function () {
    $patientUser = User::create([
        'name' => 'Khadija Sy',
        'email' => 'khadija.sy@example.com',
        'password' => bcrypt('password'),
        'role' => UserRole::PATIENT,
    ]);

    $patient = Patient::create([
        'user_id' => $patientUser->id,
        'numero_dossier' => 'PAT-2026-0002',
        'genre' => 'F',
    ]);

    /** @var DossierMedicalRepositoryInterface $repo */
    $repo = app(DossierMedicalRepositoryInterface::class);

    $dossier = $repo->createForPatient($patient->id, [
        'allergies' => 'Penicilline, Arachides',
        'antecedents_personnels' => 'Asthme leger',
    ]);

    expect($dossier)->toBeInstanceOf(DossierMedical::class)
        ->and($dossier->allergies)->toBe('Penicilline, Arachides');

    // Ajout d'une constante vitale
    $constante = $repo->addConstante($dossier->id, [
        'poids_kg' => 64.5,
        'taille_cm' => 170,
        'temperature_celsius' => 37.2,
        'tension_systolique' => 120,
        'tension_diastolique' => 80,
        'frequence_cardiaque' => 72,
        'date_mesure' => now(),
    ]);

    expect($constante)->toBeInstanceOf(Constante::class)
        ->and((float) $constante->poids_kg)->toBe(64.5);

    $latest = $repo->getLatestConstantes($dossier->id);
    expect($latest)->toHaveCount(1);

    // Ajout d'un document
    $doc = $repo->addDocument($dossier->id, [
        'nom' => 'Bilan sanguin complet',
        'type_document' => 'Biologie',
        'chemin_fichier' => 'documents/dossier_1/bilan.pdf',
        'taille_octets' => 102400,
        'mime_type' => 'application/pdf',
    ]);

    expect($repo->getDocuments($dossier->id))->toHaveCount(1)
        ->and($doc->nom)->toBe('Bilan sanguin complet');
});

test('ConsultationRepository::createWithOrdonnance enregistre la consultation et les prescriptions dans une transaction', function () {
    $service = Service::create(['nom' => 'Pediatrie', 'code' => 'PED']);
    $medUser = User::create(['name' => 'Dr Mariama', 'email' => 'mariama@example.com', 'password' => 'secret', 'role' => UserRole::MEDECIN]);
    $medecin = Medecin::create(['user_id' => $medUser->id, 'service_id' => $service->id, 'matricule' => 'MED-123', 'specialite' => 'Pediatre']);

    $patUser = User::create(['name' => 'Bebe Aliou', 'email' => 'aliou@example.com', 'password' => 'secret', 'role' => UserRole::PATIENT]);
    $patient = Patient::create(['user_id' => $patUser->id, 'numero_dossier' => 'PAT-PED-01', 'genre' => 'M']);
    $dossier = DossierMedical::create(['patient_id' => $patient->id]);

    $debut = now()->subHour();
    $rdv = RendezVous::create([
        'patient_id' => $patient->id,
        'medecin_id' => $medecin->id,
        'service_id' => $service->id,
        'date_heure_debut' => $debut,
        'date_heure_fin' => (clone $debut)->addMinutes(30),
        'motif' => 'Fievre et toux',
        'statut' => AppointmentStatus::CONFIRME,
    ]);

    /** @var ConsultationRepositoryInterface $repo */
    $repo = app(ConsultationRepositoryInterface::class);

    $consultation = $repo->createWithOrdonnance(
        consultationData: [
            'dossier_medical_id' => $dossier->id,
            'medecin_id' => $medecin->id,
            'rendez_vous_id' => $rdv->id,
            'date_heure' => now(),
            'motif_consultation' => 'Syndrome grippal',
            'diagnostic' => 'Rhinopharyngite virale',
            'conduite_a_tenir' => 'Hydratation et repos',
        ],
        ordonnanceData: [
            'instructions_generales' => 'Prendre pendant les repas',
        ],
        prescriptions: [
            [
                'nom_medicament' => 'Paracetamol Sirop',
                'forme' => 'Sirop',
                'dosage' => '15 mg/kg',
                'posologie' => 'Toutes les 6 heures si fievre',
                'duree' => '5 jours',
            ],
            [
                'nom_medicament' => 'Serum physiologique',
                'forme' => 'Dosettes',
                'dosage' => '5 ml',
                'posologie' => '3 fois par jour',
                'duree' => '7 jours',
            ],
        ]
    );

    expect($consultation)->toBeInstanceOf(Consultation::class)
        ->and($consultation->ordonnance)->not->toBeNull()
        ->and($consultation->ordonnance->prescriptions)->toHaveCount(2);

    // Le rendez-vous associe doit etre automatiquement passe a TERMINE
    expect($rdv->fresh()->statut)->toBe(AppointmentStatus::TERMINE);

    // Cloture de consultation
    $cloturee = $repo->cloturer($consultation->id);
    expect($cloturee->est_cloturee)->toBeTrue()
        ->and($cloturee->cloturee_le)->not->toBeNull();
});

test('JournalAuditRepository enregistre immuablement les acces aux donnees de sante (CDP)', function () {
    $user = User::create([
        'name' => 'Admin Systeme',
        'email' => 'admin@dme.sn',
        'password' => bcrypt('password'),
        'role' => UserRole::ADMIN,
    ]);

    /** @var JournalAuditRepositoryInterface $repo */
    $repo = app(JournalAuditRepositoryInterface::class);

    $log = $repo->log(
        userId: $user->id,
        action: AuditAction::CONSULTATION_DOSSIER,
        entiteType: 'DossierMedical',
        entiteId: 42,
        description: 'Acces au dossier medical du patient PAT-2026-0001',
        ip: '192.168.1.10',
        userAgent: 'Mozilla/5.0 DME-Client',
        details: ['motif' => 'Audit de conformite CDP']
    );

    expect($log->action)->toBe(AuditAction::CONSULTATION_DOSSIER)
        ->and($log->entite_type)->toBe('DossierMedical')
        ->and($log->details_json)->toBe(['motif' => 'Audit de conformite CDP']);

    $recent = $repo->getRecentLogs(10);
    expect($recent)->toHaveCount(1);

    $filtered = $repo->filterLogs(userId: $user->id, action: AuditAction::CONSULTATION_DOSSIER);
    expect($filtered->total())->toBe(1);
});
