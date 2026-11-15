<?php

declare(strict_types=1);

namespace App\DTOs\Consultation;

use App\DTOs\Contracts\BuilderInterface;
use Carbon\Carbon;
use InvalidArgumentException;

class CreateConsultationDTOBuilder implements BuilderInterface
{
    private ?int $dossierMedicalId = null;

    private ?int $medecinId = null;

    private ?int $rendezVousId = null;

    private ?Carbon $dateHeure = null;

    private string $motifConsultation = '';

    private ?string $histoireMaladie = null;

    private ?string $examenPhysique = null;

    private string $diagnostic = '';

    private ?string $conduiteATenir = null;

    private bool $estCloturee = false;

    private ?Carbon $clotureeLe = null;

    public static function create(): self
    {
        return new self;
    }

    public function forDossier(int $dossierMedicalId): self
    {
        $this->dossierMedicalId = $dossierMedicalId;

        return $this;
    }

    public function conductedBy(int $medecinId): self
    {
        $this->medecinId = $medecinId;

        return $this;
    }

    public function linkedToRendezVous(?int $rendezVousId): self
    {
        $this->rendezVousId = $rendezVousId;

        return $this;
    }

    public function at(Carbon|string|null $date): self
    {
        $this->dateHeure = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function withMotif(string $motif): self
    {
        $this->motifConsultation = trim($motif);

        return $this;
    }

    public function withHistoireMaladie(?string $histoire): self
    {
        $this->histoireMaladie = $histoire ? trim($histoire) : null;

        return $this;
    }

    public function withExamenPhysique(?string $examen): self
    {
        $this->examenPhysique = $examen ? trim($examen) : null;

        return $this;
    }

    public function withDiagnostic(string $diagnostic): self
    {
        $this->diagnostic = trim($diagnostic);

        return $this;
    }

    public function withConduiteATenir(?string $cat): self
    {
        $this->conduiteATenir = $cat ? trim($cat) : null;

        return $this;
    }

    public function cloturer(?Carbon $dateCloture = null): self
    {
        $this->estCloturee = true;
        $this->clotureeLe = $dateCloture ?? Carbon::now();

        return $this;
    }

    public function build(): CreateConsultationDTO
    {
        if ($this->dossierMedicalId === null) {
            throw new InvalidArgumentException('Le dossierMedicalId est obligatoire.');
        }

        if ($this->medecinId === null) {
            throw new InvalidArgumentException('Le medecinId est obligatoire.');
        }

        if (empty($this->motifConsultation)) {
            throw new InvalidArgumentException('Le motif de consultation est obligatoire.');
        }

        if (empty($this->diagnostic)) {
            throw new InvalidArgumentException('Le diagnostic médical est obligatoire.');
        }

        return new CreateConsultationDTO(
            dossierMedicalId: $this->dossierMedicalId,
            medecinId: $this->medecinId,
            rendezVousId: $this->rendezVousId,
            dateHeure: $this->dateHeure ?? Carbon::now(),
            motifConsultation: $this->motifConsultation,
            histoireMaladie: $this->histoireMaladie,
            examenPhysique: $this->examenPhysique,
            diagnostic: $this->diagnostic,
            conduiteATenir: $this->conduiteATenir,
            estCloturee: $this->estCloturee,
            clotureeLe: $this->clotureeLe,
        );
    }
}
