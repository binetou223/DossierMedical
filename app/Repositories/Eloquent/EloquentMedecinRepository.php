<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Medecin;
use App\Repositories\Contracts\MedecinRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implémentation Eloquent du dépôt des médecins (LSP / DIP).
 *
 * @extends BaseRepository<Medecin>
 */
class EloquentMedecinRepository extends BaseRepository implements MedecinRepositoryInterface
{
    public function __construct(Medecin $model)
    {
        parent::__construct($model);
    }

    public function findByService(int $serviceId): Collection
    {
        return $this->model->newQuery()
            ->with(['user', 'service'])
            ->where('service_id', $serviceId)
            ->get();
    }

    public function findByUserId(int $userId): ?Medecin
    {
        return $this->model->newQuery()
            ->with(['user', 'service'])
            ->where('user_id', $userId)
            ->first();
    }

    public function findBySpecialite(string $specialite): Collection
    {
        return $this->model->newQuery()
            ->with(['user', 'service'])
            ->where('specialite', 'like', "%{$specialite}%")
            ->get();
    }

    public function allWithDetails(): Collection
    {
        return $this->model->newQuery()
            ->with(['user', 'service'])
            ->get();
    }
}
