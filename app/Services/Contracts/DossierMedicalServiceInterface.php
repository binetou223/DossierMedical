<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\DTOs\Constante\CreateConstanteDTO;
use App\DTOs\DocumentMedical\UploadDocumentMedicalDTO;
use App\DTOs\DossierMedical\UpdateDossierMedicalDTO;
use App\Models\Constante;
use App\Models\DocumentMedical;
use App\Models\DossierMedical;

/**
 * Contrat du Service de Dossier Médical et données cliniques (SRP / DIP).
 */
interface DossierMedicalServiceInterface
{
    /**
     * Récupère le dossier médical complet d'un patient.
     */
    public function getDossierByPatient(int $patientId): ?DossierMedical;

    /**
     * Met à jour les antécédents, allergies et informations cliniques.
     */
    public function updateDossier(int $id, UpdateDossierMedicalDTO $dto): DossierMedical;

    /**
     * Enregistre une prise de constantes vitales, calcule automatiquement l'IMC et retourne les alertes cliniques.
     *
     * @return array{constante: Constante, alertes: array<int, array<string, mixed>>}
     */
    public function recordConstantes(CreateConstanteDTO $dto): array;

    /**
     * Attache un document médical joint (bilan, radio, compte-rendu) au dossier.
     */
    public function attachDocument(UploadDocumentMedicalDTO $dto): DocumentMedical;
}
