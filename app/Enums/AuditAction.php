<?php

declare(strict_types=1);

namespace App\Enums;

enum AuditAction: string
{
    case CONNEXION = 'CONNEXION';
    case DECONNEXION = 'DECONNEXION';
    case CONSULTATION_DOSSIER = 'CONSULTATION_DOSSIER';
    case MODIFICATION_DOSSIER = 'MODIFICATION_DOSSIER';
    case PRISE_RDV = 'PRISE_RDV';
    case MODIFICATION_RDV = 'MODIFICATION_RDV';
    case ENREGISTREMENT_CONSULTATION = 'ENREGISTREMENT_CONSULTATION';
    case CLOTURE_CONSULTATION = 'CLOTURE_CONSULTATION';
    case IMPRESSION_ORDONNANCE = 'IMPRESSION_ORDONNANCE';

    public function label(): string
    {
        return match ($this) {
            self::CONNEXION => 'Connexion utilisateur',
            self::DECONNEXION => 'Déconnexion utilisateur',
            self::CONSULTATION_DOSSIER => 'Consultation de dossier médical',
            self::MODIFICATION_DOSSIER => 'Mise à jour de dossier médical',
            self::PRISE_RDV => 'Prise de rendez-vous',
            self::MODIFICATION_RDV => 'Modification de statut de RDV',
            self::ENREGISTREMENT_CONSULTATION => 'Saisie de consultation',
            self::CLOTURE_CONSULTATION => 'Clôture de consultation',
            self::IMPRESSION_ORDONNANCE => 'Impression / Téléchargement d\'ordonnance',
        };
    }
}
