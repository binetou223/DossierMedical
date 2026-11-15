<?php

declare(strict_types=1);

namespace App\DTOs\RendezVous;

use App\DTOs\Contracts\BuilderInterface;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;
use InvalidArgumentException;

class CreateRendezVousDTOBuilder implements BuilderInterface
{
    private ?int $patientId = null;

    private ?int $medecinId = null;

    private ?int $serviceId = null;

    private ?Carbon $dateHeureDebut = null;

    private ?Carbon $dateHeureFin = null;

    private string $motif = '';

    private AppointmentStatus $statut = AppointmentStatus::EN_ATTENTE;

    private ?string $notesInternes = null;

    public static function create(): self
    {
        return new self;
    }

    public function forPatient(int $patientId): self
    {
        $this->patientId = $patientId;

        return $this;
    }

    public function withMedecin(int $medecinId): self
    {
        $this->medecinId = $medecinId;

        return $this;
    }

    public function inService(int $serviceId): self
    {
        $this->serviceId = $serviceId;

        return $this;
    }

    public function startsAt(Carbon|string $date): self
    {
        $this->dateHeureDebut = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function endsAt(Carbon|string|null $date): self
    {
        $this->dateHeureFin = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function withMotif(string $motif): self
    {
        $this->motif = trim($motif);

        return $this;
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

    public function withNotes(?string $notes): self
    {
        $this->notesInternes = $notes ? trim($notes) : null;

        return $this;
    }

    public function build(): CreateRendezVousDTO
    {
        if ($this->patientId === null) {
            throw new InvalidArgumentException('Le patientId est obligatoire.');
        }

        if ($this->medecinId === null) {
            throw new InvalidArgumentException('Le medecinId est obligatoire.');
        }

        if ($this->serviceId === null) {
            throw new InvalidArgumentException('Le serviceId est obligatoire.');
        }

        if ($this->dateHeureDebut === null) {
            throw new InvalidArgumentException('La date et heure de début est obligatoire.');
        }

        if (empty($this->motif)) {
            throw new InvalidArgumentException('Le motif du rendez-vous est obligatoire.');
        }

        $dateFin = $this->dateHeureFin ?? (clone $this->dateHeureDebut)->addMinutes(30);

        return new CreateRendezVousDTO(
            patientId: $this->patientId,
            medecinId: $this->medecinId,
            serviceId: $this->serviceId,
            dateHeureDebut: $this->dateHeureDebut,
            dateHeureFin: $dateFin,
            motif: $this->motif,
            statut: $this->statut,
            notesInternes: $this->notesInternes,
        );
    }
}
