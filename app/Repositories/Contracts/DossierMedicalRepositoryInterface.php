<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Constante;
use App\Models\DocumentMedical;
use App\Models\DossierMedical;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrat de persistance pour les dossiers médicaux et éléments cliniques (ISP / DIP).
 *
 * @extends BaseRepositoryInterface<DossierMedical>
 */
interface DossierMedicalRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Récupère le dossier médical complet d'un patient avec ses relations.
     */
    public function findByPatientId(int $patientId): ?DossierMedical;

    /**
     * Crée un dossier médical initial pour un patient.
     *
     * @param  array<string, mixed>  $data
     */
    public function createForPatient(int $patientId, array $data = []): DossierMedical;

    /**
     * Met à jour les antécédents, allergies ou informations confidentielles du dossier.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): DossierMedical;

    /**
     * Enregistre une nouvelle prise de constantes vitales dans le dossier.
     *
     * @param  array<string, mixed>  $constanteData
     */
    public function addConstante(int $dossierId, array $constanteData): Constante;

    /**
     * Récupère les dernières constantes mesurées pour le dossier.
     *
     * @return Collection<int, Constante>
     */
    public function getLatestConstantes(int $dossierId, int $limit = 5): Collection;

    /**
     * Attache un document médical numérisé au dossier.
     *
     * @param  array<string, mixed>  $documentData
     */
    public function addDocument(int $dossierId, array $documentData): DocumentMedical;

    /**
     * Récupère tous les documents joints au dossier médical.
     *
     * @return Collection<int, DocumentMedical>
     */
    public function getDocuments(int $dossierId): Collection;
}
