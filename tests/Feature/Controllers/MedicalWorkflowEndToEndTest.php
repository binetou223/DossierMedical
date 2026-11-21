<?php

declare(strict_types=1);

use App\Enums\AppointmentStatus;
use App\Enums\BloodGroup;
use App\Enums\UserRole;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

test('Workflow Medical Complet E2E: Patient, Secretaire, Medecin et Administrateur', function () {
    // 1. Initialisation des acteurs et de la structure hospitaliere
    $serviceCardio = Service::create(['nom' => 'Cardiologie', 'code' => 'CARDIO']);

    $adminUser = User::factory()->create([
        'name' => 'Admin Directeur',
        'email' => 'admin.test@dme.sn',
        'role' => UserRole::ADMIN,
        'is_active' => true,
    ]);

    $medUser = User::factory()->create([
        'name' => 'Dr Sow',
        'email' => 'sow.test@dme.sn',
        'role' => UserRole::MEDECIN,
        'is_active' => true,
    ]);
    $medecin = Medecin::create([
        'user_id' => $medUser->id,
        'service_id' => $serviceCardio->id,
        'matricule' => 'MED-SOW-01',
        'specialite' => 'Cardiologue',
    ]);

    $secUser = User::factory()->create([
        'name' => 'Secretaire Aminata',
        'email' => 'aminata.test@dme.sn',
        'role' => UserRole::SECRETAIRE,
        'is_active' => true,
    ]);

    $patUser = User::factory()->create([
        'name' => 'Mariama Ba',
        'email' => 'mariama.test@dme.sn',
        'role' => UserRole::PATIENT,
        'is_active' => true,
    ]);
    $patient = Patient::create([
        'user_id' => $patUser->id,
        'numero_dossier' => 'PAT-TEST-001',
        'genre' => 'F',
        'groupe_sanguin' => BloodGroup::O_POS,
    ]);
    $dossier = DossierMedical::create([
        'patient_id' => $patient->id,
    ]);

    // 2. Action PATIENT : Accès à son propre espace et consultation de son dossier
    $respPatientDossier = $this->actingAs($patUser)->get(route('dossiers.mon-dossier'));
    $respPatientDossier->assertRedirect(route('dossiers.show', $dossier->id));

    $respShowDossier = $this->actingAs($patUser)->get(route('dossiers.show', $dossier->id));
    $respShowDossier->assertOk();

    // 3. Action PATIENT : Prise de rendez-vous en ligne
    $debutRdv = Carbon::now()->addDays(2)->setHour(10)->setMinute(0)->setSecond(0);
    $respCreateRdv = $this->actingAs($patUser)->post(route('rendez-vous.store'), [
        'patient_id' => $patient->id,
        'medecin_id' => $medecin->id,
        'service_id' => $serviceCardio->id,
        'date_heure_debut' => $debutRdv->toDateTimeString(),
        'date_heure_fin' => (clone $debutRdv)->addMinutes(30)->toDateTimeString(),
        'motif' => 'Palpitations et essoufflement a l effort',
    ]);
    $respCreateRdv->assertRedirect(route('rendez-vous.index'));

    $rdv = RendezVous::where('patient_id', $patient->id)->firstOrFail();
    expect($rdv->statut)->toBe(AppointmentStatus::EN_ATTENTE);

    // 4. Action SECRÉTAIRE : Confirmation du rendez-vous
    $respConfirmRdv = $this->actingAs($secUser)->patch(route('rendez-vous.confirm', $rdv->id));
    $respConfirmRdv->assertRedirect();
    expect($rdv->fresh()->statut)->toBe(AppointmentStatus::CONFIRME);

    // 5. Action MÉDECIN : Prise de constantes vitales générant une alerte clinique
    $respConstante = $this->actingAs($medUser)->post(route('dossiers.constantes.store'), [
        'dossier_medical_id' => $dossier->id,
        'poids' => 85.0,
        'taille' => 165.0,
        'tension_systolique' => 150,
        'tension_diastolique' => 95,
        'frequence_cardiaque' => 88,
        'temperature' => 37.2,
    ]);
    $respConstante->assertRedirect();

    // 6. Action MÉDECIN : Enregistrement de la consultation avec ordonnance (médicaments)
    $respConsultation = $this->actingAs($medUser)->post(route('consultations.store'), [
        'dossier_medical_id' => $dossier->id,
        'medecin_id' => $medecin->id,
        'rendez_vous_id' => $rdv->id,
        'date_heure' => now()->toDateTimeString(),
        'motif_consultation' => 'Suivi cardiologique et HTA',
        'examen_physique' => 'Bruits du coeur reguliers, pas de souffle. TA elevee.',
        'diagnostic' => 'Hypertension arterielle essentielle stade 2',
        'ordonnance' => [
            'instructions_generales' => 'Traitement a suivre pendant 1 mois avec surveillance tensionnelle.',
            'prescriptions' => [
                [
                    'nom_medicament' => 'Amlodipine 5mg',
                    'dosage' => '5mg',
                    'forme' => 'Comprimé',
                    'posologie' => '1 comprimé par jour le matin',
                    'duree' => '30 jours',
                    'instructions' => 'A prendre au cours du petit dejeuner',
                ],
                [
                    'nom_medicament' => 'Ramipril 5mg',
                    'dosage' => '5mg',
                    'forme' => 'Gélule',
                    'posologie' => '1 comprimé par jour le soir',
                    'duree' => '30 jours',
                    'instructions' => 'Surveiller la tension',
                ],
            ],
        ],
    ]);
    $respConsultation->assertRedirect();

    $consultation = Consultation::where('dossier_medical_id', $dossier->id)->firstOrFail();
    expect($consultation->ordonnance)->not->toBeNull()
        ->and($consultation->ordonnance->prescriptions)->toHaveCount(2);

    // 7. Action MÉDECIN : Clôture de la consultation
    $respCloture = $this->actingAs($medUser)->patch(route('consultations.cloturer', $consultation->id));
    $respCloture->assertRedirect();
    expect($consultation->fresh()->est_cloturee)->toBeTrue();

    // 8. Action PATIENT : Impression de l'ordonnance médicale
    $respPrint = $this->actingAs($patUser)->get(route('consultations.ordonnance.print', $consultation->id));
    $respPrint->assertOk();

    // 9. Action ADMINISTRATEUR : Supervision des KPIs et traçabilité d'audit
    $respAdminDashboard = $this->actingAs($adminUser)->get(route('admin.dashboard'));
    $respAdminDashboard->assertOk();

    $respAdminAudit = $this->actingAs($adminUser)->get(route('admin.audit.index'));
    $respAdminAudit->assertOk();

    $respAdminUsers = $this->actingAs($adminUser)->get(route('admin.users.index'));
    $respAdminUsers->assertOk();
});
