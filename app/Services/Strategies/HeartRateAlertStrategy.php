<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Models\Constante;
use App\Services\Strategies\Contracts\VitalsAlertStrategyInterface;

/**
 * Stratégie d'évaluation du rythme cardiaque (Pattern Strategy - OCP).
 */
class HeartRateAlertStrategy implements VitalsAlertStrategyInterface
{
    public function getMetricName(): string
    {
        return 'Fréquence Cardiaque';
    }

    public function evaluate(Constante $constante): ?array
    {
        $fc = $constante->frequence_cardiaque;

        if ($fc === null) {
            return null;
        }

        if ($fc > 100) {
            return [
                'type' => 'POULS_TACHYCARDIE',
                'niveau' => 'warning',
                'message' => "Tachycardie ({$fc} bpm au repos).",
                'valeur' => $fc,
            ];
        }

        if ($fc < 50) {
            return [
                'type' => 'POULS_BRADYCARDIE',
                'niveau' => 'warning',
                'message' => "Bradycardie marquée ({$fc} bpm au repos).",
                'valeur' => $fc,
            ];
        }

        return null;
    }
}
