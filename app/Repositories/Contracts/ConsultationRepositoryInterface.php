<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Consultation;
use App\Models\Ordonnance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contrat de persistance pour les consultations et ordonnances (ISP / DIP).
 *
 * @extends BaseRepositoryInterface<Consultation>
 */
interface ConsultationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Liste paginée des consultations associées à un dossier médical.
     *
     * @return LengthAwarePaginator<Consultation>
     */
    public function findByDossierMedical(int $dossierMedicalId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Liste paginée des consultations menées par un médecin.
     *
     * @return LengthAwarePaginator<Consultation>
     */
    public function findByMedecin(int $medecinId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Crée une consultation médicale avec éventuellement son ordonnance et ses lignes de prescriptions.
     *
     * @param  array<string, mixed>  $consultationData
     * @param  array<string, mixed>|null  $ordonnanceData
     * @param  array<int, array<string, mixed>>  $prescriptions
     */
    public function createWithOrdonnance(
        array $consultationData,
        ?array $ordonnanceData = null,
        array $prescriptions = []
    ): Consultation;

    /**
     * Clôture définitivement une consultation médicale.
     */
    public function cloturer(int $id): Consultation;

    /**
     * Récupère l'ordonnance et ses prescriptions pour une consultation donnée.
     */
    public function getOrdonnance(int $consultationId): ?Ordonnance;

    /**
     * Nombre total de consultations réalisées.
     */
    public function count(): int;
}
