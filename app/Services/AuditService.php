<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AuditAction;
use App\Models\JournalAudit;
use App\Repositories\Contracts\JournalAuditRepositoryInterface;
use App\Services\Contracts\AuditServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service de traçabilité et sécurité des données de santé (Loi CDP Sénégal) (SRP & DIP).
 */
class AuditService implements AuditServiceInterface
{
    public function __construct(
        protected JournalAuditRepositoryInterface $auditRepository,
    ) {}

    public function logAccess(
        int $userId,
        AuditAction $action,
        string $entiteType,
        int $entiteId,
        string $description,
        ?string $ip = null,
        ?string $userAgent = null,
        ?array $details = null
    ): JournalAudit {
        $ip = $ip ?? request()->ip();
        $userAgent = $userAgent ?? request()->userAgent();

        return $this->auditRepository->log(
            userId: $userId,
            action: $action,
            entiteType: $entiteType,
            entiteId: $entiteId,
            description: $description,
            ip: $ip,
            userAgent: $userAgent,
            details: $details
        );
    }

    public function getRecentAuditLogs(int $limit = 50): Collection
    {
        return $this->auditRepository->getRecentLogs($limit);
    }

    public function filterAuditLogs(
        ?int $userId = null,
        ?AuditAction $action = null,
        ?string $entiteType = null,
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->auditRepository->filterLogs($userId, $action, $entiteType, $perPage);
    }
}
