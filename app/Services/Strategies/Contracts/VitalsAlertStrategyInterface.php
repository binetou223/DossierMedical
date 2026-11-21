<?php

declare(strict_types=1);

namespace App\Services\Strategies\Contracts;

use App\Models\Constante;

/**
 * Contrat pour le Pattern Strategy appliqué à l'évaluation des constantes vitales (OCP / DIP).
 * Permet d'ajouter de nouveaux algorithmes de détection d'alertes médicales sans modifier le code existant.
 */
interface VitalsAlertStrategyInterface
{
    /**
     * Nom de la métrique analysée (ex: IMC, Pression Artérielle, Glycémie).
     */
    public function getMetricName(): string;

    /**
     * Évalue une prise de constantes et renvoie les éventuelles alertes détectées.
     *
     * @return array<string, mixed>|null Tableau avec 'type', 'niveau' ('info', 'warning', 'danger'), 'message', 'valeur' ou null si tout est normal.
     */
    public function evaluate(Constante $constante): ?array;
}
