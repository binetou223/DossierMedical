<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;

/**
 * Règle d'autorisation pour les profils patients (Policy Pattern).
 */
class PatientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isMedecin() || $user->isSecretaire() || $user->isAdmin();
    }

    public function view(User $user, Patient $patient): bool
    {
        if ($user->isMedecin() || $user->isSecretaire() || $user->isAdmin()) {
            return true;
        }

        return $user->id === $patient->user_id;
    }

    public function create(User $user): bool
    {
        return $user->isSecretaire() || $user->isAdmin();
    }

    public function update(User $user, Patient $patient): bool
    {
        if ($user->isSecretaire() || $user->isAdmin()) {
            return true;
        }

        return $user->id === $patient->user_id;
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->isAdmin();
    }
}
