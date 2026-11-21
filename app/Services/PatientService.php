<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Patient\CreatePatientDTO;
use App\DTOs\Patient\UpdatePatientDTO;
use App\Enums\AuditAction;
use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;
use App\Services\Contracts\AuditServiceInterface;
use App\Services\Contracts\PatientServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service Métier pour la gestion des patients (SRP & DIP).
 */
class PatientService implements PatientServiceInterface
{
    public function __construct(
        protected PatientRepositoryInterface $patientRepository,
        protected AuditServiceInterface $auditService,
    ) {}

    public function registerPatient(CreatePatientDTO $dto): Patient
    {
        $userData = [
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
            'telephone' => $dto->telephone,
            'adresse' => $dto->adresse,
        ];

        $patientData = [
            'numero_dossier' => $dto->numeroDossier,
            'date_naissance' => $dto->dateNaissance?->toDateString(),
            'genre' => $dto->genre,
            'groupe_sanguin' => $dto->groupeSanguin,
            'profession' => $dto->profession,
            'contact_urgence_nom' => $dto->contactUrgenceNom,
            'contact_urgence_tel' => $dto->contactUrgenceTel,
        ];

        $patient = $this->patientRepository->createWithUser($userData, $patientData);

        $this->auditService->logAccess(
            userId: $patient->user_id,
            action: AuditAction::MODIFICATION_DOSSIER,
            entiteType: 'Patient',
            entiteId: $patient->id,
            description: "Création du compte et ouverture du dossier médical pour {$patient->user->name}",
            details: ['numero_dossier' => $patient->numero_dossier]
        );

        return $patient;
    }

    public function updatePatient(int $id, UpdatePatientDTO $dto): Patient
    {
        $patientData = array_filter([
            'date_naissance' => $dto->dateNaissance?->toDateString(),
            'genre' => $dto->genre,
            'groupe_sanguin' => $dto->groupeSanguin,
            'profession' => $dto->profession,
            'contact_urgence_nom' => $dto->contactUrgenceNom,
            'contact_urgence_tel' => $dto->contactUrgenceTel,
        ], fn ($val) => $val !== null);

        $patient = $this->patientRepository->update($id, $patientData);

        // Mise à jour éventuelle des infos utilisateur
        if ($dto->name || $dto->telephone || $dto->adresse) {
            $userPayload = array_filter([
                'name' => $dto->name,
                'telephone' => $dto->telephone,
                'adresse' => $dto->adresse,
            ], fn ($val) => $val !== null);

            $patient->user->update($userPayload);
        }

        return $patient->fresh(['user', 'dossierMedical']);
    }

    public function getByNumeroDossier(string $numeroDossier): ?Patient
    {
        return $this->patientRepository->findByNumeroDossier($numeroDossier);
    }

    public function getByUserId(int $userId): ?Patient
    {
        return $this->patientRepository->findByUserId($userId);
    }

    public function searchPatients(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return $this->patientRepository->search($term, $perPage);
    }
}
