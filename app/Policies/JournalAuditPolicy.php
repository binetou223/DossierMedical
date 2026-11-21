<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\JournalAudit;
use App\Models\User;

/**
 * Règle d'autorisation pour le journal d'audit de sécurité (Policy Pattern).
 * Seuls les administrateurs du système ont le droit de consulter les traces d'audit.
 */
class JournalAuditPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, JournalAudit $audit): bool
    {
        return $user->isAdmin();
    }
}
