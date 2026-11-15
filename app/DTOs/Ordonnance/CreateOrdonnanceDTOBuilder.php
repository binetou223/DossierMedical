<?php

declare(strict_types=1);

namespace App\DTOs\Ordonnance;

use App\DTOs\Contracts\BuilderInterface;
use Carbon\Carbon;
use InvalidArgumentException;

class CreateOrdonnanceDTOBuilder implements BuilderInterface
{
    private ?int $consultationId = null;

    private ?string $numeroReference = null;

    private ?Carbon $dateOrdonnance = null;

    private ?string $instructionsGenerales = null;

    /** @var array<PrescriptionItemDTO> */
    private array $prescriptions = [];

    public static function create(): self
    {
        return new self;
    }

    public function forConsultation(int $consultationId): self
    {
        $this->consultationId = $consultationId;

        return $this;
    }

    public function withReference(?string $reference): self
    {
        $this->numeroReference = $reference ? trim($reference) : null;

        return $this;
    }

    public function atDate(Carbon|string|null $date): self
    {
        $this->dateOrdonnance = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function withInstructions(?string $instructions): self
    {
        $this->instructionsGenerales = $instructions ? trim($instructions) : null;

        return $this;
    }

    public function addPrescription(PrescriptionItemDTO $prescription): self
    {
        $this->prescriptions[] = $prescription;

        return $this;
    }

    /**
     * @param  array<PrescriptionItemDTO>  $prescriptions
     */
    public function withPrescriptions(array $prescriptions): self
    {
        $this->prescriptions = $prescriptions;

        return $this;
    }

    public function build(): CreateOrdonnanceDTO
    {
        if ($this->consultationId === null) {
            throw new InvalidArgumentException('Le consultationId est obligatoire pour créer une ordonnance.');
        }

        return new CreateOrdonnanceDTO(
            consultationId: $this->consultationId,
            numeroReference: $this->numeroReference,
            dateOrdonnance: $this->dateOrdonnance ?? Carbon::now(),
            instructionsGenerales: $this->instructionsGenerales,
            prescriptions: $this->prescriptions,
        );
    }
}
