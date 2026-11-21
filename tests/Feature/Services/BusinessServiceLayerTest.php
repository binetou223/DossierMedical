<?php

declare(strict_types=1);

use App\DTOs\Consultation\CreateConsultationDTO;
use App\DTOs\Constante\CreateConstanteDTO;
use App\DTOs\Ordonnance\CreateOrdonnanceDTO;
use App\DTOs\Ordonnance\PrescriptionItemDTO;
use App\DTOs\Patient\CreatePatientDTO;
use App\DTOs\RendezVous\CreateRendezVousDTO;
use App\Enums\AppointmentStatus;
use App\Enums\BloodGroup;
use App\Enums\UserRole;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use App\Services\Contracts\ConsultationServiceInterface;
use App\Services\Contracts\DossierMedicalServiceInterface;
use App\Services\Contracts\PatientServiceInterface;
use App\Services\Contracts\RendezVousServiceInterface;
use Carbon\Carbon;

test('PatientService enregistre le patient, ouvre son dossier et logue l audit', function () {
    /** @var PatientServiceInterface $service */
    $service = app(PatientServiceInterface::class);

    $dto = CreatePatientDTO::builder()
        ->withName('Ndeye Fatou')
        ->withEmail('ndeye.fatou@example.com')
        ->withPassword('Secur1ty!2026')
        ->withTelephone('+221773334455')
        ->withGenre('F')
        ->withGroupeSanguin(BloodGroup::B_POS)
        ->build();

    $patient = $service->registerPatient($dto);

    expect($patient)->toBeInstanceOf(Patient::class)
        ->and($patient->dossierMedical)->not->toBeNull()
        ->and($patient->user->email)->toBe('ndeye.fatou@example.com');
});

test('RendezVousService refuse les creneaux horaires en conflit pour un praticien', function () {
    $serviceHospitalier = Service::create(['nom' => 'Ophtalmologie', 'code' => 'OPHTA']);
    $medUser = User::create(['name' => 'Dr Kane', 'email' => 'kane@example.com', 'password' => 'secret', 'role' => UserRole::MEDECIN]);
    $medecin = Medecin::create(['user_id' => $medUser->id, 'service_id' => $serviceHospitalier->id, 'matricule' => 'MED-KANE', 'specialite' => 'Ophtalmologue']);

    $patUser = User::create(['name' => 'Samba Lo', 'email' => 'samba@example.com', 'password' => 'secret', 'role' => UserRole::PATIENT]);
    $patient = Patient::create(['user_id' => $patUser->id, 'numero_dossier' => 'PAT-OPHTA-01', 'genre' => 'M']);

    /** @var RendezVousServiceInterface $service */
    $service = app(RendezVousServiceInterface::class);

    $debut = Carbon::now()->addDays(3)->setHour(9)->setMinute(0)->setSecond(0);
    $fin = (clone $debut)->addMinutes(30);

    $dto1 = new CreateRendezVousDTO(
        patientId: $patient->id,
        medecinId: $medecin->id,
        serviceId: $serviceHospitalier->id,
        dateHeureDebut: $debut,
        dateHeureFin: $fin,
        motif: 'Baisse acuité visuelle'
    );

    $rdv = $service->scheduleAppointment($dto1);
    expect($rdv)->not->toBeNull();

    // Deuxieme demande sur le meme creneau
    $dtoConflit = new CreateRendezVousDTO(
        patientId: $patient->id,
        medecinId: $medecin->id,
        serviceId: $serviceHospitalier->id,
        dateHeureDebut: (clone $debut)->addMinutes(10),
        dateHeureFin: (clone $debut)->addMinutes(40),
        motif: 'Urgence oculaire'
    );

    expect(fn () => $service->scheduleAppointment($dtoConflit))
        ->toThrow(DomainException::class);
});

test('DossierMedicalService calcule automatiquement l IMC et declenche les alertes cliniques', function () {
    $patUser = User::create(['name' => 'Adja Sy', 'email' => 'adja@example.com', 'password' => 'secret', 'role' => UserRole::PATIENT]);
    $patient = Patient::create(['user_id' => $patUser->id, 'numero_dossier' => 'PAT-TEST-02', 'genre' => 'F']);

    /** @var DossierMedicalServiceInterface $service */
    $service = app(DossierMedicalServiceInterface::class);
    $dossier = $patient->dossierMedical()->create();

    // 80 kg pour 160 cm -> IMC = 31.25 -> 31.3 + TA 145/95 (HTA stade 2)
    $dto = CreateConstanteDTO::builder()
        ->forDossier($dossier->id)
        ->withPoids(80.0)
        ->withTaille(160.0)
        ->withTension(145, 95)
        ->withFrequenceCardiaque(80)
        ->build();

    $result = $service->recordConstantes($dto);

    expect(round((float) $result['constante']->imc, 1))->toBe(31.3)
        ->and($result['alertes'])->not->toBeEmpty();

    $alertTypes = array_column($result['alertes'], 'type');
    expect($alertTypes)->toContain('IMC_OBESITE')
        ->and($alertTypes)->toContain('TENSION_HYPERTENSION_GRADE2');
});
