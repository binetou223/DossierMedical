<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Models\Constante;
use App\Services\Strategies\Contracts\VitalsAlertStrategyInterface;

/**
 * Gestionnaire de stratégies d'alertes cliniques (Pattern Composite & Strategy Manager).
 * Orchestre l'évaluation de toutes les stratégies enregistrées sans modifier les appelants (OCP).
 */
class VitalsAlertManager
{
    /**
     * @var array<int, VitalsAlertStrategyInterface>
     */
    protected array $strategies = [];

    /**
     * @param  array<int, VitalsAlertStrategyInterface>  $strategies
     */
    public function __construct(array $strategies = [])
    {
        $this->strategies = empty($strategies)
            ? [
                new ImcAlertStrategy,
                new BloodPressureAlertStrategy,
                new GlycemiaAlertStrategy,
                new HeartRateAlertStrategy,
            ]
            : $strategies;
    }

    /**
     * Enregistre dynamiquement une nouvelle stratégie d'analyse (Open/Closed Principle).
     */
    public function registerStrategy(VitalsAlertStrategyInterface $strategy): self
    {
        $this->strategies[] = $strategy;

        return $this;
    }

    /**
     * Analyse une prise de constantes à travers toutes les stratégies actives.
     *
     * @return array<int, array<string, mixed>> Liste des alertes identifiées.
     */
    public function evaluateAll(Constante $constante): array
    {
        $alerts = [];

        foreach ($this->strategies as $strategy) {
            $result = $strategy->evaluate($constante);
            if ($result !== null) {
                $alerts[] = $result;
            }
        }

        return $alerts;
    }

    /**
     * Indique si une alerte critique (danger) est présente.
     */
    public function hasCriticalAlert(Constante $constante): bool
    {
        foreach ($this->evaluateAll($constante) as $alert) {
            if (($alert['niveau'] ?? '') === 'danger') {
                return true;
            }
        }

        return false;
    }
}
