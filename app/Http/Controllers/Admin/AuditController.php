<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AuditAction;
use App\Http\Controllers\Controller;
use App\Models\JournalAudit;
use App\Services\Contracts\AuditServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur pour la consultation du Journal d'Audit de Sécurité (Loi CDP Sénégal).
 */
class AuditController extends Controller
{
    public function __construct(
        protected AuditServiceInterface $auditService,
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', JournalAudit::class);

        $actionString = $request->query('action');
        $action = $actionString ? AuditAction::tryFrom((string) $actionString) : null;
        $entiteType = $request->query('entite_type') ? (string) $request->query('entite_type') : null;
        $userId = $request->query('user_id') ? (int) $request->query('user_id') : null;

        $logs = $this->auditService->filterAuditLogs(
            userId: $userId,
            action: $action,
            entiteType: $entiteType,
            perPage: 25
        );

        return Inertia::render('Admin/Audit/Index', [
            'logs' => $logs,
            'actions' => AuditAction::cases(),
            'filters' => [
                'action' => $actionString,
                'entite_type' => $entiteType,
                'user_id' => $userId,
            ],
        ]);
    }
}
