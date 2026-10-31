<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case MEDECIN = 'medecin';
    case SECRETAIRE = 'secretaire';
    case PATIENT = 'patient';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::MEDECIN => 'Médecin',
            self::SECRETAIRE => 'Secrétaire médicale',
            self::PATIENT => 'Patient',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::ADMIN => 'purple',
            self::MEDECIN => 'blue',
            self::SECRETAIRE => 'emerald',
            self::PATIENT => 'gray',
        };
    }
}
