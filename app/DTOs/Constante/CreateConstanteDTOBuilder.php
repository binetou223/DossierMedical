<?php

declare(strict_types=1);

namespace App\DTOs\Constante;

use App\DTOs\Contracts\BuilderInterface;
use Carbon\Carbon;
use InvalidArgumentException;

class CreateConstanteDTOBuilder implements BuilderInterface
{
    private ?int $dossierMedicalId = null;

    private ?int $priseParUserId = null;

    private ?Carbon $dateMesure = null;

    private ?float $poidsKg = null;

    private ?float $tailleCm = null;

    private ?int $tensionSystolique = null;

    private ?int $tensionDiastolique = null;

    private ?int $frequenceCardiaque = null;

    private ?float $temperatureCelsius = null;

    private ?float $glycemieGL = null;

    private ?float $imc = null;

    private ?string $observations = null;

    public static function create(): self
    {
        return new self;
    }

    public function forDossier(int $dossierMedicalId): self
    {
        $this->dossierMedicalId = $dossierMedicalId;

        return $this;
    }

    public function takenBy(?int $userId): self
    {
        $this->priseParUserId = $userId;

        return $this;
    }

    public function measuredAt(Carbon|string|null $date): self
    {
        $this->dateMesure = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function withPoids(?float $poids): self
    {
        $this->poidsKg = $poids;

        return $this;
    }

    public function withTaille(?float $taille): self
    {
        $this->tailleCm = $taille;

        return $this;
    }

    public function withTension(?int $systolique, ?int $diastolique): self
    {
        $this->tensionSystolique = $systolique;
        $this->tensionDiastolique = $diastolique;

        return $this;
    }

    public function withFrequenceCardiaque(?int $fc): self
    {
        $this->frequenceCardiaque = $fc;

        return $this;
    }

    public function withTemperature(?float $temp): self
    {
        $this->temperatureCelsius = $temp;

        return $this;
    }

    public function withGlycemie(?float $glycemie): self
    {
        $this->glycemieGL = $glycemie;

        return $this;
    }

    public function withImc(?float $imc): self
    {
        $this->imc = $imc;

        return $this;
    }

    public function withObservations(?string $obs): self
    {
        $this->observations = $obs ? trim($obs) : null;

        return $this;
    }

    public function build(): CreateConstanteDTO
    {
        if ($this->dossierMedicalId === null) {
            throw new InvalidArgumentException('Le dossierMedicalId est obligatoire pour enregistrer une constante.');
        }

        return new CreateConstanteDTO(
            dossierMedicalId: $this->dossierMedicalId,
            priseParUserId: $this->priseParUserId,
            dateMesure: $this->dateMesure ?? Carbon::now(),
            poidsKg: $this->poidsKg,
            tailleCm: $this->tailleCm,
            tensionSystolique: $this->tensionSystolique,
            tensionDiastolique: $this->tensionDiastolique,
            frequenceCardiaque: $this->frequenceCardiaque,
            temperatureCelsius: $this->temperatureCelsius,
            glycemieGL: $this->glycemieGL,
            imc: $this->imc,
            observations: $this->observations,
        );
    }
}
