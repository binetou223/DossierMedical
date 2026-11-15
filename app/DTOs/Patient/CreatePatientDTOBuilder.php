<?php

declare(strict_types=1);

namespace App\DTOs\Patient;

use App\DTOs\Contracts\BuilderInterface;
use App\Enums\BloodGroup;
use Carbon\Carbon;
use InvalidArgumentException;

class CreatePatientDTOBuilder implements BuilderInterface
{
    private string $name = '';

    private string $email = '';

    private string $password = '';

    private ?string $telephone = null;

    private ?string $adresse = null;

    private ?string $numeroDossier = null;

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

    public function withName(string $name): self
    {
        $this->name = trim($name);

        return $this;
    }

    public function withEmail(string $email): self
    {
        $this->email = trim(strtolower($email));

        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function withTelephone(?string $telephone): self
    {
        $this->telephone = $telephone ? trim($telephone) : null;

        return $this;
    }

    public function withAdresse(?string $adresse): self
    {
        $this->adresse = $adresse ? trim($adresse) : null;

        return $this;
    }

    public function withNumeroDossier(?string $numeroDossier): self
    {
        $this->numeroDossier = $numeroDossier ? trim($numeroDossier) : null;

        return $this;
    }

    public function bornOn(Carbon|string|null $date): self
    {
        $this->dateNaissance = is_string($date) ? Carbon::parse($date) : $date;

        return $this;
    }

    public function withGenre(?string $genre): self
    {
        $this->genre = $genre ? strtoupper(trim($genre)) : null;

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
        $this->profession = $profession ? trim($profession) : null;

        return $this;
    }

    public function withContactUrgence(?string $nom, ?string $tel): self
    {
        $this->contactUrgenceNom = $nom ? trim($nom) : null;
        $this->contactUrgenceTel = $tel ? trim($tel) : null;

        return $this;
    }

    public function build(): CreatePatientDTO
    {
        if (empty($this->name)) {
            throw new InvalidArgumentException('Le nom du patient est obligatoire.');
        }

        if (empty($this->email)) {
            throw new InvalidArgumentException("L'adresse email est obligatoire.");
        }

        if (empty($this->password)) {
            throw new InvalidArgumentException('Le mot de passe initial est obligatoire.');
        }

        return new CreatePatientDTO(
            name: $this->name,
            email: $this->email,
            password: $this->password,
            telephone: $this->telephone,
            adresse: $this->adresse,
            numeroDossier: $this->numeroDossier,
            dateNaissance: $this->dateNaissance,
            genre: $this->genre,
            groupeSanguin: $this->groupeSanguin,
            profession: $this->profession,
            contactUrgenceNom: $this->contactUrgenceNom,
            contactUrgenceTel: $this->contactUrgenceTel,
        );
    }
}
