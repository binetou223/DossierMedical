<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DossierMedical extends Model
{
    use HasFactory;

    protected $table = 'dossiers_medicaux';

    protected $fillable = [
        'patient_id',
        'antecedents_personnels',
        'antecedents_chirurgicaux',
        'antecedents_familiaux',
        'allergies',
        'traitements_chroniques',
        'regime_alimentaire',
        'notes_confidentielles',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function constantes(): HasMany
    {
        return $this->hasMany(Constante::class)->orderByDesc('date_mesure');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DocumentMedical::class)->orderByDesc('created_at');
    }

    public function consultations(): HasMany
    {
        return $this->hasMany(Consultation::class)->orderByDesc('date_heure');
    }
}
