<?php

declare(strict_types=1);

namespace App\DTOs\Contracts;

use Illuminate\Http\Request;

interface DataTransferObjectInterface
{
    /**
     * Instancie le DTO depuis une requête HTTP validée.
     */
    public static function fromRequest(Request $request): self;

    /**
     * Instancie le DTO depuis un tableau associatif.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self;

    /**
     * Convertit le DTO en tableau compatible avec la persistance Eloquent.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
