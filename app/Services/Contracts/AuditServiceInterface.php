<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Enums\AuditAction;
use App\Models\JournalAudit;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrat du Service de Journal d'Audit et conformité CDP Sénégal (SRP / DIP).
 */
interface AuditServiceInterface
{
    /**
     * Enregistre un événement de sécurité ou un accès aux données de santé.
     *
     * @param  array<string, mixed>|null  $details
     */
    public function logAccess(
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
     * Récupère les entrées récentes d'audit pour le tableau de bord administrateur.
     *
     * @return Collection<int, JournalAudit>
     */
    public function getRecentAuditLogs(int $limit = 50): Collection;

    /**
     * Filtre multicritère pour les rapports de conformité.
     *
     * @return LengthAwarePaginator<JournalAudit>
     */
    public function filterAuditLogs(
        ?int $userId = null,
        ?AuditAction $action = null,
        ?string $entiteType = null,
        int $perPage = 20
    ): LengthAwarePaginator;
}
