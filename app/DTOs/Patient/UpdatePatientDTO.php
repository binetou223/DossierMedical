<?php

declare(strict_types=1);

namespace App\DTOs\Patient;

use App\DTOs\Contracts\DataTransferObjectInterface;
use App\Enums\BloodGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class UpdatePatientDTO implements DataTransferObjectInterface
{
    public function __construct(
        public ?string $name = null,
        public ?string $telephone = null,
        public ?string $adresse = null,
        public ?Carbon $dateNaissance = null,
        public ?string $genre = null,
        public ?BloodGroup $groupeSanguin = null,
        public ?string $profession = null,
        public ?string $contactUrgenceNom = null,
        public ?string $contactUrgenceTel = null,
    ) {}

    public static function builder(): UpdatePatientDTOBuilder
    {
        return UpdatePatientDTOBuilder::create();
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
            name: isset($data['name']) ? (string) $data['name'] : null,
            telephone: $data['telephone'] ?? null,
            adresse: $data['adresse'] ?? null,
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
        $result = [];

        if ($this->name !== null) {
            $result['name'] = $this->name;
        }
        if ($this->telephone !== null) {
            $result['telephone'] = $this->telephone;
        }
        if ($this->adresse !== null) {
            $result['adresse'] = $this->adresse;
        }
        if ($this->dateNaissance !== null) {
            $result['date_naissance'] = $this->dateNaissance->toDateString();
        }
        if ($this->genre !== null) {
            $result['genre'] = $this->genre;
        }
        if ($this->groupeSanguin !== null) {
            $result['groupe_sanguin'] = $this->groupeSanguin->value;
        }
        if ($this->profession !== null) {
            $result['profession'] = $this->profession;
        }
        if ($this->contactUrgenceNom !== null) {
            $result['contact_urgence_nom'] = $this->contactUrgenceNom;
        }
        if ($this->contactUrgenceTel !== null) {
            $result['contact_urgence_tel'] = $this->contactUrgenceTel;
        }

        return $result;
    }
}
