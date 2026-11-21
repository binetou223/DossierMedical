<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\AppointmentStatus;
use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\PrescriptionMedicament;
use App\Models\RendezVous;
use App\Repositories\Contracts\ConsultationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Implémentation Eloquent du dépôt des consultations et ordonnances (LSP / DIP).
 *
 * @extends BaseRepository<Consultation>
 */
class EloquentConsultationRepository extends BaseRepository implements ConsultationRepositoryInterface
{
    public function __construct(Consultation $model)
    {
        parent::__construct($model);
    }

    public function findByDossierMedical(int $dossierMedicalId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->with(['medecin.user', 'ordonnance.prescriptions', 'rendezVous'])
            ->where('dossier_medical_id', $dossierMedicalId)
            ->orderByDesc('date_heure')
            ->paginate($perPage);
    }

    public function findByMedecin(int $medecinId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->with(['dossierMedical.patient.user', 'ordonnance.prescriptions'])
            ->where('medecin_id', $medecinId)
            ->orderByDesc('date_heure')
            ->paginate($perPage);
    }

    public function createWithOrdonnance(
        array $consultationData,
        ?array $ordonnanceData = null,
        array $prescriptions = []
    ): Consultation {
        return DB::transaction(function () use ($consultationData, $ordonnanceData, $prescriptions) {
            /** @var Consultation $consultation */
            $consultation = $this->model->newQuery()->create($consultationData);

            if ($ordonnanceData !== null) {
                if (empty($ordonnanceData['numero_reference'])) {
                    $ordonnanceData['numero_reference'] = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(5));
                }
                if (empty($ordonnanceData['date_ordonnance'])) {
                    $ordonnanceData['date_ordonnance'] = now()->toDateString();
                }

                $ordonnanceData['consultation_id'] = $consultation->id;
                /** @var Ordonnance $ordonnance */
                $ordonnance = Ordonnance::create($ordonnanceData);

                foreach ($prescriptions as $item) {
                    $item['ordonnance_id'] = $ordonnance->id;
                    PrescriptionMedicament::create($item);
                }
            }

            // Marquer le rendez-vous lié comme honoré/terminé
            if (! empty($consultationData['rendez_vous_id'])) {
                RendezVous::where('id', $consultationData['rendez_vous_id'])
                    ->update(['statut' => AppointmentStatus::TERMINE]);
            }

            return $consultation->load(['medecin.user', 'ordonnance.prescriptions']);
        });
    }

    public function cloturer(int $id): Consultation
    {
        $consultation = $this->findOrFail($id);
        $consultation->update([
            'est_cloturee' => true,
            'cloturee_le' => now(),
        ]);

        return $consultation->fresh(['medecin.user', 'ordonnance.prescriptions']);
    }

    public function getOrdonnance(int $consultationId): ?Ordonnance
    {
        return Ordonnance::with('prescriptions')
            ->where('consultation_id', $consultationId)
            ->first();
    }

    public function count(): int
    {
        return $this->model->newQuery()->count();
    }
}
