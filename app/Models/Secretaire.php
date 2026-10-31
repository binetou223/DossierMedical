<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Secretaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'poste',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
