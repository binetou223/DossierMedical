<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Constante;

class ConstanteObserver
{
    /**
     * Handle the Constante "saving" event.
     */
    public function saving(Constante $constante): void
    {
        if ($constante->poids_kg && $constante->taille_cm && (float) $constante->taille_cm > 0) {
            $tailleEnMetres = (float) $constante->taille_cm / 100;
            $imc = (float) $constante->poids_kg / ($tailleEnMetres * $tailleEnMetres);
            $constante->imc = round($imc, 1);
        }
    }
}
