<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentMedical extends Model
{
    use HasFactory;

    protected $table = 'documents_medicaux';

    protected $fillable = [
        'dossier_medical_id',
        'nom',
        'type_document',
        'chemin_fichier',
        'taille_octets',
        'ajoute_par_user_id',
    ];

    protected function casts(): array
    {
        return [
            'taille_octets' => 'integer',
        ];
    }

    public function dossierMedical(): BelongsTo
    {
        return $this->belongsTo(DossierMedical::class);
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ajoute_par_user_id');
    }
}
