<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'service_id',
        'date_heure_debut',
        'date_heure_fin',
        'motif',
        'statut',
        'motif_annulation',
        'notes_internes',
    ];

    protected function casts(): array
    {
        return [
            'statut' => AppointmentStatus::class,
            'date_heure_debut' => 'datetime',
            'date_heure_fin' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function consultation(): HasOne
    {
        return $this->hasOne(Consultation::class);
    }
}
