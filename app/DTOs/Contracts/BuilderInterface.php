<?php

declare(strict_types=1);

namespace App\DTOs\Contracts;

/**
 * Contrat pour tous les Builders de DTOs.
 */
interface BuilderInterface
{
    /**
     * Crée une nouvelle instance fluide du Builder.
     */
    public static function create(): self;

    /**
     * Valide et produit le DTO immuable final.
     */
    public function build(): DataTransferObjectInterface;
}
