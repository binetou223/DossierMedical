<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Models\Constante;
use App\Services\Strategies\Contracts\VitalsAlertStrategyInterface;

/**
 * Stratégie d'évaluation de l'Indice de Masse Corporelle (IMC) (Pattern Strategy - OCP).
 */
class ImcAlertStrategy implements VitalsAlertStrategyInterface
{
    public function getMetricName(): string
    {
        return 'Indice de Masse Corporelle (IMC)';
    }

    public function evaluate(Constante $constante): ?array
    {
        $imc = $constante->imc !== null ? (float) $constante->imc : null;

        // Si l'IMC n'est pas encore calculé, le déduire du poids et de la taille
        if ($imc === null && $constante->poids_kg && $constante->taille_cm) {
            $tailleM = ((float) $constante->taille_cm) / 100.0;
            if ($tailleM > 0) {
                $imc = round(((float) $constante->poids_kg) / ($tailleM * $tailleM), 1);
            }
        }

        if ($imc === null) {
            return null;
        }

        if ($imc < 18.5) {
            return [
                'type' => 'IMC_MAIGREUR',
                'niveau' => 'warning',
                'message' => "Insuffisance pondérale (IMC: {$imc} kg/m²). Risque de dénutrition.",
                'valeur' => $imc,
            ];
        }

        if ($imc >= 25.0 && $imc < 30.0) {
            return [
                'type' => 'IMC_SURPOIDS',
                'niveau' => 'warning',
                'message' => "Surpoids constaté (IMC: {$imc} kg/m²). Surveillance diététique conseillée.",
                'valeur' => $imc,
            ];
        }

        if ($imc >= 30.0) {
            return [
                'type' => 'IMC_OBESITE',
                'niveau' => 'danger',
                'message' => "Obésité clinique (IMC: {$imc} kg/m²). Bilan cardiovasculaire recommandé.",
                'valeur' => $imc,
            ];
        }

        return null;
    }
}
