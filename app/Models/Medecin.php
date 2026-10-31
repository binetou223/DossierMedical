<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medecin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'matricule',
        'specialite',
        'titre',
        'bureau',
        'duree_consultation_minutes',
        'jours_consultation',
    ];

    protected function casts(): array
    {
        return [
            'jours_consultation' => 'array',
            'duree_consultation_minutes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function getNomCompletAttribute(): string
    {
        return "{$this->titre} {$this->user->name}";
    }
}
