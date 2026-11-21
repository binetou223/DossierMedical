<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Implémentation générique de base pour Eloquent (LSP).
 *
 * @template TModel of Model
 *
 * @implements BaseRepositoryInterface<TModel>
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * @param  TModel  $model
     */
    public function __construct(
        protected Model $model
    ) {}

    /**
     * @param  array<int, string>  $relations
     * @return TModel|null
     */
    public function findById(int $id, array $relations = []): ?Model
    {
        return $this->model->newQuery()->with($relations)->find($id);
    }

    /**
     * @param  array<int, string>  $relations
     * @return TModel
     */
    public function findOrFail(int $id, array $relations = []): Model
    {
        return $this->model->newQuery()->with($relations)->findOrFail($id);
    }

    /**
     * @param  array<int, string>  $columns
     * @param  array<int, string>  $relations
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->newQuery()->with($relations)->get($columns);
    }

    /**
     * @param  array<int, string>  $relations
     * @return LengthAwarePaginator<TModel>
     */
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->model->newQuery()->with($relations)->paginate($perPage);
    }

    /**
     * Supprime une entité par son ID.
     */
    public function delete(int $id): bool
    {
        $entity = $this->findById($id);

        if (! $entity) {
            return false;
        }

        return (bool) $entity->delete();
    }
}
