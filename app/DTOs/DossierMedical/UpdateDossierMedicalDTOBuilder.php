<?php

declare(strict_types=1);

namespace App\DTOs\DossierMedical;

use App\DTOs\Contracts\BuilderInterface;

class UpdateDossierMedicalDTOBuilder implements BuilderInterface
{
    private ?string $antecedentsPersonnels = null;

    private ?string $antecedentsChirurgicaux = null;

    private ?string $antecedentsFamiliaux = null;

    private ?string $allergies = null;

    private ?string $traitementsChroniques = null;

    private ?string $regimeAlimentaire = null;

    private ?string $notesConfidentielles = null;

    public static function create(): self
    {
        return new self;
    }

    public function withAntecedentsPersonnels(?string $ant): self
    {
        $this->antecedentsPersonnels = $ant !== null ? trim($ant) : null;

        return $this;
    }

    public function withAntecedentsChirurgicaux(?string $ant): self
    {
        $this->antecedentsChirurgicaux = $ant !== null ? trim($ant) : null;

        return $this;
    }

    public function withAntecedentsFamiliaux(?string $ant): self
    {
        $this->antecedentsFamiliaux = $ant !== null ? trim($ant) : null;

        return $this;
    }

    public function withAllergies(?string $allergies): self
    {
        $this->allergies = $allergies !== null ? trim($allergies) : null;

        return $this;
    }

    public function withTraitementsChroniques(?string $traitements): self
    {
        $this->traitementsChroniques = $traitements !== null ? trim($traitements) : null;

        return $this;
    }

    public function withRegimeAlimentaire(?string $regime): self
    {
        $this->regimeAlimentaire = $regime !== null ? trim($regime) : null;

        return $this;
    }

    public function withNotesConfidentielles(?string $notes): self
    {
        $this->notesConfidentielles = $notes !== null ? trim($notes) : null;

        return $this;
    }

    public function build(): UpdateDossierMedicalDTO
    {
        return new UpdateDossierMedicalDTO(
            antecedentsPersonnels: $this->antecedentsPersonnels,
            antecedentsChirurgicaux: $this->antecedentsChirurgicaux,
            antecedentsFamiliaux: $this->antecedentsFamiliaux,
            allergies: $this->allergies,
            traitementsChroniques: $this->traitementsChroniques,
            regimeAlimentaire: $this->regimeAlimentaire,
            notesConfidentielles: $this->notesConfidentielles,
        );
    }
}
