<?php

declare(strict_types=1);

namespace App\DTOs\Ordonnance;

use App\DTOs\Contracts\DataTransferObjectInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class CreateOrdonnanceDTO implements DataTransferObjectInterface
{
    /** @var array<PrescriptionItemDTO> */
    public array $items;

    /**
     * @param  array<PrescriptionItemDTO>  $prescriptions
     */
    public function __construct(
        public ?int $consultationId = null,
        public ?string $numeroReference = null,
        public ?Carbon $dateOrdonnance = null,
        public ?string $instructionsGenerales = null,
        public array $prescriptions = [],
    ) {
        $this->items = $prescriptions;
    }

    public static function builder(): CreateOrdonnanceDTOBuilder
    {
        return CreateOrdonnanceDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $prescriptions = [];
        if (! empty($data['prescriptions']) && is_array($data['prescriptions'])) {
            foreach ($data['prescriptions'] as $item) {
                if ($item instanceof PrescriptionItemDTO) {
                    $prescriptions[] = $item;
                } elseif (is_array($item)) {
                    $prescriptions[] = PrescriptionItemDTO::fromArray($item);
                }
            }
        }

        return new self(
            consultationId: (int) $data['consultation_id'],
            numeroReference: $data['numero_reference'] ?? null,
            dateOrdonnance: ! empty($data['date_ordonnance']) ? Carbon::parse($data['date_ordonnance']) : Carbon::now(),
            instructionsGenerales: $data['instructions_generales'] ?? null,
            prescriptions: $prescriptions,
        );
    }

    public function toArray(): array
    {
        return [
            'consultation_id' => $this->consultationId,
            'numero_reference' => $this->numeroReference,
            'date_ordonnance' => ($this->dateOrdonnance ?? Carbon::now())->toDateString(),
            'instructions_generales' => $this->instructionsGenerales,
            'prescriptions' => array_map(
                static fn (PrescriptionItemDTO $item): array => $item->toArray(),
                $this->prescriptions
            ),
        ];
    }
}
