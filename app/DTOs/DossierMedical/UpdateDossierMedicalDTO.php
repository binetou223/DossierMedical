<?php

declare(strict_types=1);

namespace App\DTOs\DossierMedical;

use App\DTOs\Contracts\DataTransferObjectInterface;
use Illuminate\Http\Request;

readonly class UpdateDossierMedicalDTO implements DataTransferObjectInterface
{
    public function __construct(
        public ?string $antecedentsPersonnels = null,
        public ?string $antecedentsChirurgicaux = null,
        public ?string $antecedentsFamiliaux = null,
        public ?string $allergies = null,
        public ?string $traitementsChroniques = null,
        public ?string $regimeAlimentaire = null,
        public ?string $notesConfidentielles = null,
    ) {}

    public static function builder(): UpdateDossierMedicalDTOBuilder
    {
        return UpdateDossierMedicalDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            antecedentsPersonnels: $data['antecedents_personnels'] ?? null,
            antecedentsChirurgicaux: $data['antecedents_chirurgicaux'] ?? null,
            antecedentsFamiliaux: $data['antecedents_familiaux'] ?? null,
            allergies: $data['allergies'] ?? null,
            traitementsChroniques: $data['traitements_chroniques'] ?? null,
            regimeAlimentaire: $data['regime_alimentaire'] ?? null,
            notesConfidentielles: $data['notes_confidentielles'] ?? null,
        );
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->antecedentsPersonnels !== null) {
            $result['antecedents_personnels'] = $this->antecedentsPersonnels;
        }
        if ($this->antecedentsChirurgicaux !== null) {
            $result['antecedents_chirurgicaux'] = $this->antecedentsChirurgicaux;
        }
        if ($this->antecedentsFamiliaux !== null) {
            $result['antecedents_familiaux'] = $this->antecedentsFamiliaux;
        }
        if ($this->allergies !== null) {
            $result['allergies'] = $this->allergies;
        }
        if ($this->traitementsChroniques !== null) {
            $result['traitements_chroniques'] = $this->traitementsChroniques;
        }
        if ($this->regimeAlimentaire !== null) {
            $result['regime_alimentaire'] = $this->regimeAlimentaire;
        }
        if ($this->notesConfidentielles !== null) {
            $result['notes_confidentielles'] = $this->notesConfidentielles;
        }

        return $result;
    }
}
