<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Constante extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_medical_id',
        'prise_par_user_id',
        'date_mesure',
        'poids_kg',
        'taille_cm',
        'tension_systolique',
        'tension_diastolique',
        'frequence_cardiaque',
        'temperature_celsius',
        'glycemie_g_l',
        'imc',
        'observations',
    ];

    protected function casts(): array
    {
        return [
            'date_mesure' => 'datetime',
            'poids_kg' => 'decimal:2',
            'taille_cm' => 'decimal:2',
            'temperature_celsius' => 'decimal:1',
            'glycemie_g_l' => 'decimal:2',
            'imc' => 'decimal:1',
            'tension_systolique' => 'integer',
            'tension_diastolique' => 'integer',
            'frequence_cardiaque' => 'integer',
        ];
    }

    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class);
    }

    public function agentPrise(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prise_par_user_id');
    }
}
