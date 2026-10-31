<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ordonnance extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'numero_reference',
        'date_ordonnance',
        'instructions_generales',
    ];

    protected function casts(): array
    {
        return [
            'date_ordonnance' => 'date',
        ];
    }

    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(PrescriptionMedicament::class);
    }
}
