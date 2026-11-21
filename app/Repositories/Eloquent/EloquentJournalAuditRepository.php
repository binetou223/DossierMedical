<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\AuditAction;
use App\Models\JournalAudit;
use App\Repositories\Contracts\JournalAuditRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Implémentation Eloquent du journal d'audit pour la sécurité et la traçabilité CDP (LSP / DIP).
 *
 * @extends BaseRepository<JournalAudit>
 */
class EloquentJournalAuditRepository extends BaseRepository implements JournalAuditRepositoryInterface
{
    public function __construct(JournalAudit $model)
    {
        parent::__construct($model);
    }

    public function log(
        int $userId,
        AuditAction $action,
        string $entiteType,
        int $entiteId,
        string $description,
        ?string $ip = null,
        ?string $userAgent = null,
        ?array $details = null
    ): JournalAudit {
        /** @var JournalAudit $audit */
        $audit = $this->model->newQuery()->create([
            'user_id' => $userId,
            'action' => $action,
            'entite_type' => $entiteType,
            'entite_id' => $entiteId,
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'details_json' => $details,
            'created_at' => now(),
        ]);

        return $audit->load('user');
    }

    public function getRecentLogs(int $limit = 50): Collection
    {
        return $this->model->newQuery()
            ->with('user')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function filterLogs(
        ?int $userId = null,
        ?AuditAction $action = null,
        ?string $entiteType = null,
        int $perPage = 20
    ): LengthAwarePaginator {
        $query = $this->model->newQuery()->with('user');

        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        if ($action !== null) {
            $query->where('action', $action);
        }

        if ($entiteType !== null) {
            $query->where('entite_type', $entiteType);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }
}
