<?php

declare(strict_types=1);

namespace App\DTOs\Ordonnance;

use App\DTOs\Contracts\DataTransferObjectInterface;
use Illuminate\Http\Request;

readonly class PrescriptionItemDTO implements DataTransferObjectInterface
{
    public function __construct(
        public string $nomMedicament,
        public ?string $dosage = null,
        public ?string $forme = null,
        public string $posologie = '',
        public string $duree = '',
        public ?string $instructions = null,
    ) {}

    public static function builder(): PrescriptionItemDTOBuilder
    {
        return PrescriptionItemDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            nomMedicament: (string) ($data['nom_medicament'] ?? ''),
            dosage: $data['dosage'] ?? null,
            forme: $data['forme'] ?? null,
            posologie: (string) ($data['posologie'] ?? ''),
            duree: (string) ($data['duree'] ?? ''),
            instructions: $data['instructions'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'nom_medicament' => $this->nomMedicament,
            'dosage' => $this->dosage,
            'forme' => $this->forme,
            'posologie' => $this->posologie,
            'duree' => $this->duree,
            'instructions' => $this->instructions,
        ];
    }
}
