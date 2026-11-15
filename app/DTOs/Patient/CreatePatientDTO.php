<?php

declare(strict_types=1);

namespace App\DTOs\Patient;

use App\DTOs\Contracts\DataTransferObjectInterface;
use App\Enums\BloodGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class CreatePatientDTO implements DataTransferObjectInterface
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $telephone = null,
        public ?string $adresse = null,
        public ?string $numeroDossier = null,
        public ?Carbon $dateNaissance = null,
        public ?string $genre = null,
        public ?BloodGroup $groupeSanguin = null,
        public ?string $profession = null,
        public ?string $contactUrgenceNom = null,
        public ?string $contactUrgenceTel = null,
    ) {}

    public static function builder(): CreatePatientDTOBuilder
    {
        return CreatePatientDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $groupeSanguin = null;
        if (! empty($data['groupe_sanguin'])) {
            $groupeSanguin = $data['groupe_sanguin'] instanceof BloodGroup
                ? $data['groupe_sanguin']
                : BloodGroup::tryFrom((string) $data['groupe_sanguin']);
        }

        return new self(
            name: (string) ($data['name'] ?? ''),
            email: (string) ($data['email'] ?? ''),
            password: (string) ($data['password'] ?? ''),
            telephone: $data['telephone'] ?? null,
            adresse: $data['adresse'] ?? null,
            numeroDossier: $data['numero_dossier'] ?? null,
            dateNaissance: ! empty($data['date_naissance']) ? Carbon::parse($data['date_naissance']) : null,
            genre: $data['genre'] ?? null,
            groupeSanguin: $groupeSanguin,
            profession: $data['profession'] ?? null,
            contactUrgenceNom: $data['contact_urgence_nom'] ?? null,
            contactUrgenceTel: $data['contact_urgence_tel'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'telephone' => $this->telephone,
            'adresse' => $this->adresse,
            'numero_dossier' => $this->numeroDossier,
            'date_naissance' => $this->dateNaissance?->toDateString(),
            'genre' => $this->genre,
            'groupe_sanguin' => $this->groupeSanguin?->value,
            'profession' => $this->profession,
            'contact_urgence_nom' => $this->contactUrgenceNom,
            'contact_urgence_tel' => $this->contactUrgenceTel,
        ];
    }
}
