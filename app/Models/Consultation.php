<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dossier_medical_id',
        'medecin_id',
        'rendez_vous_id',
        'date_heure',
        'motif_consultation',
        'histoire_maladie',
        'examen_physique',
        'diagnostic',
        'conduite_a_tenir',
        'est_cloturee',
        'cloturee_le',
    ];

    protected function casts(): array
    {
        return [
            'date_heure' => 'datetime',
            'cloturee_le' => 'datetime',
            'est_cloturee' => 'boolean',
        ];
    }

    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function rendezVous(): BelongsTo
    {
        return $this->belongsTo(RendezVous::class);
    }

    public function ordonnance(): HasOne
    {
        return $this->hasOne(Ordonnance::class);
    }
}
