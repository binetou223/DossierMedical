<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Constante;
use App\Models\DocumentMedical;
use App\Models\DossierMedical;
use App\Repositories\Contracts\DossierMedicalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implémentation Eloquent du dépôt des dossiers médicaux (LSP / DIP).
 *
 * @extends BaseRepository<DossierMedical>
 */
class EloquentDossierMedicalRepository extends BaseRepository implements DossierMedicalRepositoryInterface
{
    public function __construct(DossierMedical $model)
    {
        parent::__construct($model);
    }

    public function findByPatientId(int $patientId): ?DossierMedical
    {
        return $this->model->newQuery()
            ->with([
                'patient.user',
                'constantes' => fn ($q) => $q->latest('date_mesure')->limit(10),
                'documents' => fn ($q) => $q->latest(),
                'consultations' => fn ($q) => $q->with(['medecin.user', 'ordonnance.prescriptions'])->latest('date_heure'),
            ])
            ->where('patient_id', $patientId)
            ->first();
    }

    public function createForPatient(int $patientId, array $data = []): DossierMedical
    {
        $data['patient_id'] = $patientId;

        /** @var DossierMedical $dossier */
        $dossier = $this->model->newQuery()->create($data);

        return $dossier->load('patient.user');
    }

    public function update(int $id, array $data): DossierMedical
    {
        $dossier = $this->findOrFail($id);
        $dossier->update($data);

        return $dossier->fresh();
    }

    public function addConstante(int $dossierId, array $constanteData): Constante
    {
        $dossier = $this->findOrFail($dossierId);
        $constanteData['dossier_medical_id'] = $dossier->id;

        return Constante::create($constanteData);
    }

    public function getLatestConstantes(int $dossierId, int $limit = 5): Collection
    {
        return Constante::query()
            ->where('dossier_medical_id', $dossierId)
            ->orderByDesc('date_mesure')
            ->limit($limit)
            ->get();
    }

    public function addDocument(int $dossierId, array $documentData): DocumentMedical
    {
        $dossier = $this->findOrFail($dossierId);
        $documentData['dossier_medical_id'] = $dossier->id;

        return DocumentMedical::create($documentData);
    }

    public function getDocuments(int $dossierId): Collection
    {
        return DocumentMedical::query()
            ->where('dossier_medical_id', $dossierId)
            ->orderByDesc('created_at')
            ->get();
    }
}
