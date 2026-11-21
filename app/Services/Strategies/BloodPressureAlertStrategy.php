<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Models\Constante;
use App\Services\Strategies\Contracts\VitalsAlertStrategyInterface;

/**
 * Stratégie d'évaluation de la tension artérielle (Pattern Strategy - OCP).
 */
class BloodPressureAlertStrategy implements VitalsAlertStrategyInterface
{
    public function getMetricName(): string
    {
        return 'Tension Artérielle';
    }

    public function evaluate(Constante $constante): ?array
    {
        $sys = $constante->tension_systolique;
        $dia = $constante->tension_diastolique;

        if ($sys === null || $dia === null) {
            return null;
        }

        // Crise hypertensive / Urgence
        if ($sys >= 180 || $dia >= 120) {
            return [
                'type' => 'TENSION_CRISE_HYPERTENSIVE',
                'niveau' => 'danger',
                'message' => "Urgence hypertensive : Tension {$sys}/{$dia} mmHg. Nécessite une prise en charge immédiate.",
                'valeur' => "{$sys}/{$dia}",
            ];
        }

        // Hypertension stade 2
        if ($sys >= 140 || $dia >= 90) {
            return [
                'type' => 'TENSION_HYPERTENSION_GRADE2',
                'niveau' => 'danger',
                'message' => "Hypertension artérielle avérée : {$sys}/{$dia} mmHg.",
                'valeur' => "{$sys}/{$dia}",
            ];
        }

        // Pré-hypertension
        if ($sys >= 130 || $dia >= 85) {
            return [
                'type' => 'TENSION_PRE_HYPERTENSION',
                'niveau' => 'warning',
                'message' => "Pression artérielle élevée : {$sys}/{$dia} mmHg. À recontrôler au repos.",
                'valeur' => "{$sys}/{$dia}",
            ];
        }

        // Hypotension
        if ($sys < 90 || $dia < 60) {
            return [
                'type' => 'TENSION_HYPOTENSION',
                'niveau' => 'warning',
                'message' => "Hypotension constatée : {$sys}/{$dia} mmHg.",
                'valeur' => "{$sys}/{$dia}",
            ];
        }

        return null;
    }
}
