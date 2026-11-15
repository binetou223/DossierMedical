<?php

declare(strict_types=1);

namespace App\DTOs\Patient;

use App\DTOs\Contracts\BuilderInterface;
use App\Enums\BloodGroup;
use Carbon\Carbon;

class UpdatePatientDTOBuilder implements BuilderInterface
{
    private ?string $name = null;

    private ?string $telephone = null;

    private ?string $adresse = null;

    private ?Carbon $dateNaissance = null;

    private ?string $genre = null;

    private ?BloodGroup $groupeSanguin = null;

    private ?string $profession = null;

    private ?string $contactUrgenceNom = null;

    private ?string $contactUrgenceTel = null;

    public static function create(): self
    {
        return new self;
    }

    public function withName(?string $name): self
    {
        $this->name = $name !== null ? trim($name) : null;

        return $this;
    }

    public function withTelephone(?string $telephone): self
    {
        $this->telephone = $telephone !== null ? trim($telephone) : null;

        return $this;
    }

    public function withAdresse(?string $adresse): self
    {
        $this->adresse = $adresse !== null ? trim($adresse) : null;

        return $this;
    }

    public function bornOn(Carbon|string|null $date): self
    {
        $this->dateNaissance = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function withGenre(?string $genre): self
    {
        $this->genre = $genre !== null ? strtoupper(trim($genre)) : null;

        return $this;
    }

    public function withGroupeSanguin(BloodGroup|string|null $groupe): self
    {
        if (is_string($groupe)) {
            $this->groupeSanguin = BloodGroup::tryFrom($groupe);
        } else {
            $this->groupeSanguin = $groupe;
        }

        return $this;
    }

    public function withProfession(?string $profession): self
    {
        $this->profession = $profession !== null ? trim($profession) : null;

        return $this;
    }

    public function withContactUrgence(?string $nom, ?string $tel): self
    {
        $this->contactUrgenceNom = $nom !== null ? trim($nom) : null;
        $this->contactUrgenceTel = $tel !== null ? trim($tel) : null;

        return $this;
    }

    public function build(): UpdatePatientDTO
    {
        return new UpdatePatientDTO(
            name: $this->name,
            telephone: $this->telephone,
            adresse: $this->adresse,
            dateNaissance: $this->dateNaissance,
            genre: $this->genre,
            groupeSanguin: $this->groupeSanguin,
            profession: $this->profession,
            contactUrgenceNom: $this->contactUrgenceNom,
            contactUrgenceTel: $this->contactUrgenceTel,
        );
    }
}
