<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Enums\AuditAction;
use App\Models\JournalAudit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrat de persistance pour le journal d'audit de sécurité et traçabilité CDP (ISP / DIP).
 *
 * @extends BaseRepositoryInterface<JournalAudit>
 */
interface JournalAuditRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Enregistre une entrée immuable dans le journal d'audit de sécurité.
     *
     * @param  array<string, mixed>|null  $details
     */
    public function log(
        int $userId,
        AuditAction $action,
        string $entiteType,
        int $entiteId,
        string $description,
        ?string $ip = null,
        ?string $userAgent = null,
        ?array $details = null
    ): JournalAudit;

    /**
     * Récupère les dernières activités d'audit pour le tableau de bord administrateur.
     *
     * @return Collection<int, JournalAudit>
     */
    public function getRecentLogs(int $limit = 50): Collection;

    /**
     * Recherche et filtre multicritère des entrées du journal d'audit.
     *
     * @return LengthAwarePaginator<JournalAudit>
     */
    public function filterLogs(
        ?int $userId = null,
        ?AuditAction $action = null,
        ?string $entiteType = null,
        int $perPage = 20
    ): LengthAwarePaginator;
}
