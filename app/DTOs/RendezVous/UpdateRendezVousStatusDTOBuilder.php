<?php

declare(strict_types=1);

namespace App\DTOs\RendezVous;

use App\DTOs\Contracts\BuilderInterface;
use App\Enums\AppointmentStatus;
use InvalidArgumentException;

class UpdateRendezVousStatusDTOBuilder implements BuilderInterface
{
    private ?AppointmentStatus $statut = null;

    private ?string $motifAnnulation = null;

    private ?string $notesInternes = null;

    public static function create(): self
    {
        return new self;
    }

    public function withStatut(AppointmentStatus|string $statut): self
    {
        if (is_string($statut)) {
            $this->statut = AppointmentStatus::from($statut);
        } else {
            $this->statut = $statut;
        }

        return $this;
    }

    public function withMotifAnnulation(?string $motif): self
    {
        $this->motifAnnulation = $motif !== null ? trim($motif) : null;

        return $this;
    }

    public function withNotesInternes(?string $notes): self
    {
        $this->notesInternes = $notes !== null ? trim($notes) : null;

        return $this;
    }

    public function build(): UpdateRendezVousStatusDTO
    {
        if ($this->statut === null) {
            throw new InvalidArgumentException('Le statut du rendez-vous est obligatoire.');
        }

        if ($this->statut === AppointmentStatus::ANNULE && empty($this->motifAnnulation)) {
            throw new InvalidArgumentException('Le motif d\'annulation est obligatoire lors de l\'annulation.');
        }

        return new UpdateRendezVousStatusDTO(
            statut: $this->statut,
            motifAnnulation: $this->motifAnnulation,
            notesInternes: $this->notesInternes,
        );
    }
}
