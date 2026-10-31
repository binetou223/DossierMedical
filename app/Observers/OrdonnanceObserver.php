<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ordonnance;

class OrdonnanceObserver
{
    /**
     * Handle the Ordonnance "creating" event.
     */
    public function creating(Ordonnance $ordonnance): void
    {
        if (empty($ordonnance->numero_reference)) {
            $annee = date('Y');
            $dernierId = (int) (Ordonnance::max('id') ?? 0) + 1;
            $ordonnance->numero_reference = sprintf('ORD-%s-%04d', $annee, $dernierId);
        }

        if (empty($ordonnance->date_ordonnance)) {
            $ordonnance->date_ordonnance = now()->toDateString();
        }
    }
}
