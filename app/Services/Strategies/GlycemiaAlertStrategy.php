<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Models\Constante;
use App\Services\Strategies\Contracts\VitalsAlertStrategyInterface;

/**
 * Stratégie d'évaluation de la glycémie à jeun (Pattern Strategy - OCP).
 */
class GlycemiaAlertStrategy implements VitalsAlertStrategyInterface
{
    public function getMetricName(): string
    {
        return 'Glycémie à jeun';
    }

    public function evaluate(Constante $constante): ?array
    {
        if ($constante->glycemie_g_l === null) {
            return null;
        }

        $glycemie = (float) $constante->glycemie_g_l;

        if ($glycemie < 0.70) {
            return [
                'type' => 'GLYCEMIE_HYPOGLYCEMIE',
                'niveau' => 'danger',
                'message' => "Hypoglycémie sévère ({$glycemie} g/L). Risque de malaise immédiat.",
                'valeur' => $glycemie,
            ];
        }

        if ($glycemie >= 1.26) {
            return [
                'type' => 'GLYCEMIE_DIABETE_SUSPECT',
                'niveau' => 'danger',
                'message' => "Hyperglycémie marquée ({$glycemie} g/L). Évocation d'un diabète à confirmer.",
                'valeur' => $glycemie,
            ];
        }

        if ($glycemie >= 1.10) {
            return [
                'type' => 'GLYCEMIE_INTOLERANCE',
                'niveau' => 'warning',
                'message' => "Glycémie limite ({$glycemie} g/L). Surveillance glucidique préconisée.",
                'valeur' => $glycemie,
            ];
        }

        return null;
    }
}
