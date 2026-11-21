<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contrat de persistance pour les patients (ISP / DIP).
 *
 * @extends BaseRepositoryInterface<Patient>
 */
interface PatientRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Recherche un patient par son numéro de dossier unique.
     */
    public function findByNumeroDossier(string $numeroDossier): ?Patient;

    /**
     * Recherche le profil patient associé à un compte utilisateur.
     */
    public function findByUserId(int $userId): ?Patient;

    /**
     * Recherche multicritère de patients (nom, prénom, email, téléphone, numéro de dossier).
     *
     * @return LengthAwarePaginator<Patient>
     */
    public function search(string $term, int $perPage = 15): LengthAwarePaginator;

    /**
     * Crée un utilisateur et son profil patient avec initialisation automatique du dossier médical.
     *
     * @param  array<string, mixed>  $userData
     * @param  array<string, mixed>  $patientData
     */
    public function createWithUser(array $userData, array $patientData): Patient;

    /**
     * Met à jour les informations administratives du patient.
     *
     * @param  array<string, mixed>  $patientData
     */
    public function update(int $id, array $patientData): Patient;

    /**
     * Nombre total de patients enregistrés.
     */
    public function count(): int;
}
