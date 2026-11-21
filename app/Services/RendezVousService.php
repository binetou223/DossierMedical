<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\RendezVous\CreateRendezVousDTO;
use App\Enums\AppointmentStatus;
use App\Enums\AuditAction;
use App\Models\RendezVous;
use App\Repositories\Contracts\RendezVousRepositoryInterface;
use App\Services\Contracts\AuditServiceInterface;
use App\Services\Contracts\RendezVousServiceInterface;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service Métier pour la gestion du cycle de vie des rendez-vous (SRP & DIP).
 */
class RendezVousService implements RendezVousServiceInterface
{
    public function __construct(
        protected RendezVousRepositoryInterface $rendezVousRepository,
        protected AuditServiceInterface $auditService,
    ) {}

    public function scheduleAppointment(CreateRendezVousDTO $dto): RendezVous
    {
        // Règle Métier 1 : Détection des conflits d'horaire pour le praticien
        if ($this->rendezVousRepository->hasConflict($dto->medecinId, $dto->dateHeureDebut, $dto->dateHeureFin)) {
            throw new DomainException('Le médecin sélectionné a déjà un rendez-vous confirmé sur ce créneau horaire.');
        }

        // Règle Métier 2 : La date de début ne doit pas être dans le passé pour une nouvelle prise
        if ($dto->dateHeureDebut->isPast()) {
            throw new DomainException('Un rendez-vous ne peut pas être planifié dans le passé.');
        }

        $rdv = $this->rendezVousRepository->create([
            'patient_id' => $dto->patientId,
            'medecin_id' => $dto->medecinId,
            'service_id' => $dto->serviceId,
            'date_heure_debut' => $dto->dateHeureDebut,
            'date_heure_fin' => $dto->dateHeureFin,
            'motif' => $dto->motif,
            'statut' => $dto->statut,
            'notes_internes' => $dto->notesInternes,
        ]);

        $this->auditService->logAccess(
            userId: $rdv->patient->user_id,
            action: AuditAction::PRISE_RDV,
            entiteType: 'RendezVous',
            entiteId: $rdv->id,
            description: "Demande de rendez-vous pour le {$rdv->date_heure_debut->format('d/m/Y à H:i')}",
            details: ['motif' => $rdv->motif]
        );

        return $rdv;
    }

    public function confirmAppointment(int $id, ?string $notes = null): RendezVous
    {
        $rdv = $this->rendezVousRepository->findOrFail($id);

        if ($rdv->statut->isTerminal()) {
            throw new DomainException("Ce rendez-vous est déjà {$rdv->statut->label()} et ne peut être modifié.");
        }

        $confirmed = $this->rendezVousRepository->updateStatus($id, AppointmentStatus::CONFIRME);

        if ($notes !== null) {
            $confirmed->update(['notes_internes' => $notes]);
        }

        $this->auditService->logAccess(
            userId: auth()->id() ?? $confirmed->medecin->user_id,
            action: AuditAction::MODIFICATION_RDV,
            entiteType: 'RendezVous',
            entiteId: $confirmed->id,
            description: "Confirmation du rendez-vous n°{$confirmed->id}"
        );

        return $confirmed;
    }

    public function cancelAppointment(int $id, string $motifAnnulation): RendezVous
    {
        if (trim($motifAnnulation) === '') {
            throw new DomainException("Le motif d'annulation est obligatoire.");
        }

        $cancelled = $this->rendezVousRepository->updateStatus($id, AppointmentStatus::ANNULE, $motifAnnulation);

        $this->auditService->logAccess(
            userId: auth()->id() ?? $cancelled->patient->user_id,
            action: AuditAction::MODIFICATION_RDV,
            entiteType: 'RendezVous',
            entiteId: $cancelled->id,
            description: "Annulation du rendez-vous n°{$cancelled->id} pour le motif : {$motifAnnulation}"
        );

        return $cancelled;
    }

    public function rescheduleAppointment(int $id, CarbonInterface $nouvelleDateDebut, CarbonInterface $nouvelleDateFin): RendezVous
    {
        $rdv = $this->rendezVousRepository->findOrFail($id);

        if ($this->rendezVousRepository->hasConflict($rdv->medecin_id, $nouvelleDateDebut, $nouvelleDateFin, $rdv->id)) {
            throw new DomainException('Le médecin sélectionné a déjà un créneau occupé sur cette nouvelle plage horaire.');
        }

        $rdv->update([
            'date_heure_debut' => $nouvelleDateDebut,
            'date_heure_fin' => $nouvelleDateFin,
            'statut' => AppointmentStatus::EN_ATTENTE,
        ]);

        return $rdv->fresh(['patient.user', 'medecin.user', 'service']);
    }

    public function getUpcomingForPatient(int $patientId): Collection
    {
        return $this->rendezVousRepository->findUpcomingByPatient($patientId);
    }

    public function getDailyScheduleForMedecin(int $medecinId, ?CarbonInterface $date = null): Collection
    {
        return $this->rendezVousRepository->findByMedecin($medecinId, $date);
    }

    public function getHistoryForPatient(int $patientId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->rendezVousRepository->findByPatient($patientId, $perPage);
    }
}
