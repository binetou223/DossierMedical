<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\AppointmentStatus;
use App\Models\RendezVous;
use App\Repositories\Contracts\RendezVousRepositoryInterface;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implémentation Eloquent du dépôt des rendez-vous (LSP / DIP).
 *
 * @extends BaseRepository<RendezVous>
 */
class EloquentRendezVousRepository extends BaseRepository implements RendezVousRepositoryInterface
{
    public function __construct(RendezVous $model)
    {
        parent::__construct($model);
    }

    public function findByPatient(int $patientId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->with(['medecin.user', 'service'])
            ->where('patient_id', $patientId)
            ->orderByDesc('date_heure_debut')
            ->paginate($perPage);
    }

    public function findByMedecin(int $medecinId, ?CarbonInterface $date = null): Collection
    {
        $query = $this->model->newQuery()
            ->with(['patient.user', 'service'])
            ->where('medecin_id', $medecinId);

        if ($date !== null) {
            $query->whereDate('date_heure_debut', $date->toDateString());
        }

        return $query->orderBy('date_heure_debut')->get();
    }

    public function findUpcomingByPatient(int $patientId): Collection
    {
        return $this->model->newQuery()
            ->with(['medecin.user', 'service'])
            ->where('patient_id', $patientId)
            ->where('date_heure_debut', '>=', now())
            ->whereIn('statut', [AppointmentStatus::EN_ATTENTE, AppointmentStatus::CONFIRME])
            ->orderBy('date_heure_debut')
            ->get();
    }

    public function findUpcomingByMedecin(int $medecinId): Collection
    {
        return $this->model->newQuery()
            ->with(['patient.user', 'service'])
            ->where('medecin_id', $medecinId)
            ->where('date_heure_debut', '>=', now())
            ->whereIn('statut', [AppointmentStatus::EN_ATTENTE, AppointmentStatus::CONFIRME])
            ->orderBy('date_heure_debut')
            ->get();
    }

    public function hasConflict(int $medecinId, CarbonInterface $debut, CarbonInterface $fin, ?int $excludeRdvId = null): bool
    {
        $query = $this->model->newQuery()
            ->where('medecin_id', $medecinId)
            ->whereNotIn('statut', [AppointmentStatus::ANNULE])
            ->where(function ($q) use ($debut, $fin) {
                // Chevauchement horaire : début < fin_existante ET fin > début_existant
                $q->where('date_heure_debut', '<', $fin)
                    ->where('date_heure_fin', '>', $debut);
            });

        if ($excludeRdvId !== null) {
            $query->where('id', '!=', $excludeRdvId);
        }

        return $query->exists();
    }

    public function create(array $data): RendezVous
    {
        /** @var RendezVous $rdv */
        $rdv = $this->model->newQuery()->create($data);

        return $rdv->load(['patient.user', 'medecin.user', 'service']);
    }

    public function updateStatus(int $id, AppointmentStatus $status, ?string $motifAnnulation = null): RendezVous
    {
        $rdv = $this->findOrFail($id);
        $payload = ['statut' => $status];

        if ($motifAnnulation !== null) {
            $payload['motif_annulation'] = $motifAnnulation;
        }

        $rdv->update($payload);

        return $rdv->fresh(['patient.user', 'medecin.user', 'service']);
    }

    public function getAgenda(int $medecinId, CarbonInterface $debut, CarbonInterface $fin): Collection
    {
        return $this->model->newQuery()
            ->with(['patient.user', 'service'])
            ->where('medecin_id', $medecinId)
            ->whereBetween('date_heure_debut', [$debut, $fin])
            ->orderBy('date_heure_debut')
            ->get();
    }

    public function countByStatus(AppointmentStatus $status): int
    {
        return $this->model->newQuery()->where('statut', $status)->count();
    }
}
