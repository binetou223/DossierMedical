<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Enums\AppointmentStatus;
use App\Models\RendezVous;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrat de persistance pour la gestion des rendez-vous médicaux (ISP / DIP).
 *
 * @extends BaseRepositoryInterface<RendezVous>
 */
interface RendezVousRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Liste paginée des rendez-vous d'un patient.
     *
     * @return LengthAwarePaginator<RendezVous>
     */
    public function findByPatient(int $patientId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Liste des rendez-vous d'un médecin pour une date donnée ou générale.
     *
     * @return Collection<int, RendezVous>
     */
    public function findByMedecin(int $medecinId, ?CarbonInterface $date = null): Collection;

    /**
     * Prochains rendez-vous à venir pour un patient.
     *
     * @return Collection<int, RendezVous>
     */
    public function findUpcomingByPatient(int $patientId): Collection;

    /**
     * Prochains rendez-vous à venir pour un médecin.
     *
     * @return Collection<int, RendezVous>
     */
    public function findUpcomingByMedecin(int $medecinId): Collection;

    /**
     * Vérifie s'il existe un conflit horaire pour un médecin sur un créneau donné.
     */
    public function hasConflict(int $medecinId, CarbonInterface $debut, CarbonInterface $fin, ?int $excludeRdvId = null): bool;

    /**
     * Crée un nouveau rendez-vous.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): RendezVous;

    /**
     * Met à jour le statut d'un rendez-vous avec motif d'annulation optionnel.
     */
    public function updateStatus(int $id, AppointmentStatus $status, ?string $motifAnnulation = null): RendezVous;

    /**
     * Récupère l'agenda d'un médecin sur une période donnée (calendrier).
     *
     * @return Collection<int, RendezVous>
     */
    public function getAgenda(int $medecinId, CarbonInterface $debut, CarbonInterface $fin): Collection;

    /**
     * Compte les rendez-vous selon un statut spécifique.
     */
    public function countByStatus(AppointmentStatus $status): int;
}
