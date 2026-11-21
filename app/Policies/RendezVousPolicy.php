<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RendezVous;
use App\Models\User;

/**
 * Règle d'autorisation pour la gestion des rendez-vous (Policy Pattern).
 */
class RendezVousPolicy
{
    /**
     * Consultation de la liste générale des rendez-vous selon le rôle.
     */
    public function viewAny(User $user): bool
    {
        return $user->isMedecin() || $user->isSecretaire() || $user->isAdmin() || $user->isPatient();
    }

    /**
     * Consultation d'un rendez-vous spécifique.
     */
    public function view(User $user, RendezVous $rdv): bool
    {
        if ($user->isAdmin() || $user->isSecretaire()) {
            return true;
        }

        if ($user->isMedecin()) {
            return $user->id === $rdv->medecin->user_id;
        }

        if ($user->isPatient()) {
            return $user->id === $rdv->patient->user_id;
        }

        return false;
    }

    /**
     * Prise de rendez-vous.
     */
    public function create(User $user): bool
    {
        // Patients, secrétaires et médecins peuvent initier un rendez-vous
        return $user->isPatient() || $user->isSecretaire() || $user->isMedecin();
    }

    /**
     * Confirmation ou validation d'un rendez-vous.
     */
    public function confirm(User $user, RendezVous $rdv): bool
    {
        if ($user->isSecretaire()) {
            return true;
        }

        if ($user->isMedecin()) {
            return $user->id === $rdv->medecin->user_id;
        }

        return false;
    }

    /**
     * Annulation d'un rendez-vous.
     */
    public function cancel(User $user, RendezVous $rdv): bool
    {
        if ($rdv->statut->isTerminal()) {
            return false;
        }

        if ($user->isSecretaire() || $user->isAdmin()) {
            return true;
        }

        if ($user->isMedecin()) {
            return $user->id === $rdv->medecin->user_id;
        }

        if ($user->isPatient()) {
            return $user->id === $rdv->patient->user_id;
        }

        return false;
    }
}
