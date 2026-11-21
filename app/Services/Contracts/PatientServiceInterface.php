<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\DTOs\Patient\CreatePatientDTO;
use App\DTOs\Patient\UpdatePatientDTO;
use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contrat du Service Patient (SRP / DIP).
 */
interface PatientServiceInterface
{
    /**
     * Enregistre un patient et initialise son dossier médical.
     */
    public function registerPatient(CreatePatientDTO $dto): Patient;

    /**
     * Met à jour le profil patient.
     */
    public function updatePatient(int $id, UpdatePatientDTO $dto): Patient;

    /**
     * Recherche un patient par son numéro de dossier.
     */
    public function getByNumeroDossier(string $numeroDossier): ?Patient;

    /**
     * Recherche le profil lié à un compte utilisateur connecté.
     */
    public function getByUserId(int $userId): ?Patient;

    /**
     * Recherche multicritère de patients.
     *
     * @return LengthAwarePaginator<Patient>
     */
    public function searchPatients(string $term, int $perPage = 15): LengthAwarePaginator;
}
