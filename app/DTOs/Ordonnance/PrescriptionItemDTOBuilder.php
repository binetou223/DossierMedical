<?php

declare(strict_types=1);

namespace App\DTOs\Ordonnance;

use App\DTOs\Contracts\BuilderInterface;
use InvalidArgumentException;

class PrescriptionItemDTOBuilder implements BuilderInterface
{
    private string $nomMedicament = '';

    private ?string $dosage = null;

    private ?string $forme = null;

    private string $posologie = '';

    private string $duree = '';

    private ?string $instructions = null;

    public static function create(): self
    {
        return new self;
    }

    public function medicament(string $nom): self
    {
        $this->nomMedicament = trim($nom);

        return $this;
    }

    public function dosage(?string $dosage): self
    {
        $this->dosage = $dosage ? trim($dosage) : null;

        return $this;
    }

    public function forme(?string $forme): self
    {
        $this->forme = $forme ? trim($forme) : null;

        return $this;
    }

    public function posologie(string $posologie): self
    {
        $this->posologie = trim($posologie);

        return $this;
    }

    public function duree(string $duree): self
    {
        $this->duree = trim($duree);

        return $this;
    }

    public function withInstructions(?string $instructions): self
    {
        $this->instructions = $instructions ? trim($instructions) : null;

        return $this;
    }

    public function build(): PrescriptionItemDTO
    {
        if (empty($this->nomMedicament)) {
            throw new InvalidArgumentException('Le nom du médicament est obligatoire.');
        }

        if (empty($this->posologie)) {
            throw new InvalidArgumentException('La posologie est obligatoire.');
        }

        if (empty($this->duree)) {
            throw new InvalidArgumentException('La durée du traitement est obligatoire.');
        }

        return new PrescriptionItemDTO(
            nomMedicament: $this->nomMedicament,
            dosage: $this->dosage,
            forme: $this->forme,
            posologie: $this->posologie,
            duree: $this->duree,
            instructions: $this->instructions,
        );
    }
}
