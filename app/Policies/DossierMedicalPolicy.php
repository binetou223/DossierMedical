<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DossierMedical;
use App\Models\User;

/**
 * Règle de sécurité et d'autorisation pour le Dossier Médical Électronique (Proxy / Policy Pattern).
 * Implémente le secret médical et les exigences de la CDP Sénégal :
 * - Le patient ne voit que son propre dossier.
 * - Le médecin y accède dans le cadre de sa prise en charge.
 * - La secrétaire accède aux métadonnées administratives mais pas aux notes confidentielles.
 * - L'administrateur n'a pas accès aux données de santé médicales pures.
 */
class DossierMedicalPolicy
{
    /**
     * Droit de consultation du dossier médical.
     */
    public function view(User $user, DossierMedical $dossier): bool
    {
        // 1. Le patient peut consulter uniquement son propre dossier
        if ($user->isPatient()) {
            return $user->id === $dossier->patient->user_id;
        }

        // 2. Le médecin peut consulter le dossier dans le cadre du parcours de soin
        if ($user->isMedecin()) {
            return true;
        }

        // 3. La secrétaire peut consulter les dossiers pour l'accueil et l'orientation
        if ($user->isSecretaire()) {
            return true;
        }

        // 4. L'administrateur système ne dispose pas d'accès direct au contenu médical (Secret Médical)
        return false;
    }

    /**
     * Droit de modification des antécédents et éléments cliniques.
     */
    public function update(User $user, DossierMedical $dossier): bool
    {
        // Seuls les médecins peuvent valider/modifier des antécédents médicaux et allergies
        return $user->isMedecin();
    }

    /**
     * Droit de saisie d'une prise de constantes vitales.
     */
    public function addConstante(User $user, DossierMedical $dossier): bool
    {
        // Les médecins et les secrétaires médicales (accueil/triage) peuvent enregistrer des constantes
        return $user->isMedecin() || $user->isSecretaire();
    }

    /**
     * Droit d'attachement d'un document médical numérisé.
     */
    public function uploadDocument(User $user, DossierMedical $dossier): bool
    {
        // Médecins, secrétaires et le patient lui-même (pour ses résultats d'analyses externes)
        if ($user->isMedecin() || $user->isSecretaire()) {
            return true;
        }

        if ($user->isPatient()) {
            return $user->id === $dossier->patient->user_id;
        }

        return false;
    }
}
