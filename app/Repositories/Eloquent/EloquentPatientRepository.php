<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\UserRole;
use App\Models\DossierMedical;
use App\Models\Patient;
use App\Models\User;
use App\Repositories\Contracts\PatientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Implémentation Eloquent du dépôt des patients (LSP / DIP).
 *
 * @extends BaseRepository<Patient>
 */
class EloquentPatientRepository extends BaseRepository implements PatientRepositoryInterface
{
    public function __construct(Patient $model)
    {
        parent::__construct($model);
    }

    public function findByNumeroDossier(string $numeroDossier): ?Patient
    {
        return $this->model->newQuery()
            ->with(['user', 'dossierMedical'])
            ->where('numero_dossier', $numeroDossier)
            ->first();
    }

    public function findByUserId(int $userId): ?Patient
    {
        return $this->model->newQuery()
            ->with(['user', 'dossierMedical'])
            ->where('user_id', $userId)
            ->first();
    }

    public function search(string $term, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->with(['user', 'dossierMedical'])
            ->where(function ($query) use ($term) {
                $query->where('numero_dossier', 'like', "%{$term}%")
                    ->orWhere('contact_urgence_nom', 'like', "%{$term}%")
                    ->orWhere('contact_urgence_tel', 'like', "%{$term}%")
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%")
                            ->orWhere('telephone', 'like', "%{$term}%");
                    });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function createWithUser(array $userData, array $patientData): Patient
    {
        return DB::transaction(function () use ($userData, $patientData) {
            $userData['role'] = $userData['role'] ?? UserRole::PATIENT;
            $userData['is_active'] = $userData['is_active'] ?? true;
            $user = User::create($userData);

            $patientData['user_id'] = $user->id;
            if (empty($patientData['numero_dossier'])) {
                $patientData['numero_dossier'] = 'PAT-'.date('Y').'-'.strtoupper(Str::random(6));
            }

            /** @var Patient $patient */
            $patient = $this->model->newQuery()->create($patientData);

            DossierMedical::create([
                'patient_id' => $patient->id,
            ]);

            return $patient->load(['user', 'dossierMedical']);
        });
    }

    public function update(int $id, array $patientData): Patient
    {
        $patient = $this->findOrFail($id);
        $patient->update($patientData);

        return $patient->fresh(['user', 'dossierMedical']);
    }

    public function count(): int
    {
        return $this->model->newQuery()->count();
    }
}
