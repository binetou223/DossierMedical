<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\BloodGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'numero_dossier',
        'date_naissance',
        'genre',
        'groupe_sanguin',
        'profession',
        'contact_urgence_nom',
        'contact_urgence_tel',
    ];

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'groupe_sanguin' => BloodGroup::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dossierMedical(): HasOne
    {
        return $this->hasOne(DossierMedical::class);
    }

    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }
}
