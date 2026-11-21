<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Consultation;
use App\Models\User;

/**
 * Règle d'autorisation pour les consultations et ordonnances (Policy Pattern).
 * Garantit l'intégrité des actes médicaux et le respect du secret médical.
 */
class ConsultationPolicy
{
    /**
     * Consultation d'une consultation médicale.
     */
    public function view(User $user, Consultation $consultation): bool
    {
        // Le praticien auteur ou tout médecin ayant accès au dossier
        if ($user->isMedecin()) {
            return true;
        }

        // Le patient concerné (lecture seule de son historique de consultation)
        if ($user->isPatient()) {
            return $user->id === $consultation->dossierMedical->patient->user_id;
        }

        return false;
    }

    /**
     * Création d'une consultation médicale.
     */
    public function create(User $user): bool
    {
        // Seuls les médecins peuvent réaliser une consultation médicale
        return $user->isMedecin();
    }

    /**
     * Modification d'une consultation médicale.
     */
    public function update(User $user, Consultation $consultation): bool
    {
        // Une consultation clôturée est immuable et ne peut plus être modifiée
        if ($consultation->est_cloturee) {
            return false;
        }

        // Seul le médecin ayant rédigé la consultation peut la modifier avant clôture
        return $user->isMedecin() && $user->id === $consultation->medecin->user_id;
    }

    /**
     * Clôture définitive d'une consultation médicale.
     */
    public function cloturer(User $user, Consultation $consultation): bool
    {
        if ($consultation->est_cloturee) {
            return false;
        }

        return $user->isMedecin() && $user->id === $consultation->medecin->user_id;
    }
}
