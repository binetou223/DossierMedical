<?php

declare(strict_types=1);

namespace App\Enums;

enum AppointmentStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case CONFIRME = 'confirme';
    case ANNULE = 'annule';
    case TERMINE = 'termine';
    case ABSENT = 'absent';

    public function label(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'En attente',
            self::CONFIRME => 'Confirmé',
            self::ANNULE => 'Annulé',
            self::TERMINE => 'Terminé',
            self::ABSENT => 'Absent',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::EN_ATTENTE => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
            self::CONFIRME => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            self::ANNULE => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            self::TERMINE => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            self::ABSENT => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
        };
    }

    public function isTerminal(): bool
    {
        return in_array($this, [self::ANNULE, self::TERMINE, self::ABSENT], true);
    }
}
