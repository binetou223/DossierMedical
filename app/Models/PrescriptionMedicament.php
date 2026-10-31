<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrescriptionMedicament extends Model
{
    use HasFactory;

    protected $table = 'prescriptions_medicaments';

    protected $fillable = [
        'ordonnance_id',
        'nom_medicament',
        'dosage',
        'forme',
        'posologie',
        'duree',
        'instructions',
    ];

    public function ordonnance(): BelongsTo
    {
        return $this->belongsTo(Ordonnance::class);
    }
}
