<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\DTOs\RendezVous\CreateRendezVousDTO;
use App\Models\RendezVous;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrat du Service de gestion des rendez-vous médicaux (SRP / DIP).
 */
interface RendezVousServiceInterface
{
    /**
     * Planifie un rendez-vous après vérification de non-conflit horaire.
     */
    public function scheduleAppointment(CreateRendezVousDTO $dto): RendezVous;

    /**
     * Confirme un rendez-vous en attente.
     */
    public function confirmAppointment(int $id, ?string $notes = null): RendezVous;

    /**
     * Annule un rendez-vous avec motif obligatoire.
     */
    public function cancelAppointment(int $id, string $motifAnnulation): RendezVous;

    /**
     * Reprogramme un rendez-vous vers une nouvelle date.
     */
    public function rescheduleAppointment(int $id, CarbonInterface $nouvelleDateDebut, CarbonInterface $nouvelleDateFin): RendezVous;

    /**
     * Récupère les rendez-vous à venir d'un patient.
     *
     * @return Collection<int, RendezVous>
     */
    public function getUpcomingForPatient(int $patientId): Collection;

    /**
     * Récupère l'agenda journalier d'un médecin.
     *
     * @return Collection<int, RendezVous>
     */
    public function getDailyScheduleForMedecin(int $medecinId, ?CarbonInterface $date = null): Collection;

    /**
     * Historique paginé des rendez-vous d'un patient.
     *
     * @return LengthAwarePaginator<RendezVous>
     */
    public function getHistoryForPatient(int $patientId, int $perPage = 15): LengthAwarePaginator;
}
