<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface de base pour les dépôts (Repository Pattern - DIP & ISP).
 * Définit les opérations communes de lecture et suppression.
 *
 * @template TModel of Model
 */
interface BaseRepositoryInterface
{
    /**
     * Recherche une entité par son identifiant unique.
     *
     * @param  array<int, string>  $relations
     * @return TModel|null
     */
    public function findById(int $id, array $relations = []): ?Model;

    /**
     * Recherche une entité par son identifiant ou lève une exception ModelNotFoundException.
     *
     * @param  array<int, string>  $relations
     * @return TModel
     */
    public function findOrFail(int $id, array $relations = []): Model;

    /**
     * Récupère toutes les entités.
     *
     * @param  array<int, string>  $columns
     * @param  array<int, string>  $relations
     * @return Collection<int, TModel>
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Récupère une liste paginée d'entités.
     *
     * @param  array<int, string>  $relations
     * @return LengthAwarePaginator<TModel>
     */
    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator;

    /**
     * Supprime une entité par son identifiant.
     */
    public function delete(int $id): bool;
}
