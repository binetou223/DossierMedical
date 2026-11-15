<?php

declare(strict_types=1);

namespace App\DTOs\Constante;

use App\DTOs\Contracts\DataTransferObjectInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class CreateConstanteDTO implements DataTransferObjectInterface
{
    public function __construct(
        public int $dossierMedicalId,
        public ?int $priseParUserId = null,
        public ?Carbon $dateMesure = null,
        public ?float $poidsKg = null,
        public ?float $tailleCm = null,
        public ?int $tensionSystolique = null,
        public ?int $tensionDiastolique = null,
        public ?int $frequenceCardiaque = null,
        public ?float $temperatureCelsius = null,
        public ?float $glycemieGL = null,
        public ?float $imc = null,
        public ?string $observations = null,
    ) {}

    public static function builder(): CreateConstanteDTOBuilder
    {
        return CreateConstanteDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray([
            ...$data,
            'prise_par_user_id' => $request->user()?->id,
        ]);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            dossierMedicalId: (int) $data['dossier_medical_id'],
            priseParUserId: isset($data['prise_par_user_id']) ? (int) $data['prise_par_user_id'] : null,
            dateMesure: ! empty($data['date_mesure']) ? Carbon::parse($data['date_mesure']) : Carbon::now(),
            poidsKg: isset($data['poids_kg']) ? (float) $data['poids_kg'] : null,
            tailleCm: isset($data['taille_cm']) ? (float) $data['taille_cm'] : null,
            tensionSystolique: isset($data['tension_systolique']) ? (int) $data['tension_systolique'] : null,
            tensionDiastolique: isset($data['tension_diastolique']) ? (int) $data['tension_diastolique'] : null,
            frequenceCardiaque: isset($data['frequence_cardiaque']) ? (int) $data['frequence_cardiaque'] : null,
            temperatureCelsius: isset($data['temperature_celsius']) ? (float) $data['temperature_celsius'] : null,
            glycemieGL: isset($data['glycemie_g_l']) ? (float) $data['glycemie_g_l'] : null,
            imc: isset($data['imc']) ? (float) $data['imc'] : null,
            observations: $data['observations'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'dossier_medical_id' => $this->dossierMedicalId,
            'prise_par_user_id' => $this->priseParUserId,
            'date_mesure' => ($this->dateMesure ?? Carbon::now())->toDateTimeString(),
            'poids_kg' => $this->poidsKg,
            'taille_cm' => $this->tailleCm,
            'tension_systolique' => $this->tensionSystolique,
            'tension_diastolique' => $this->tensionDiastolique,
            'frequence_cardiaque' => $this->frequenceCardiaque,
            'temperature_celsius' => $this->temperatureCelsius,
            'glycemie_g_l' => $this->glycemieGL,
            'imc' => $this->imc,
            'observations' => $this->observations,
        ];
    }
}
