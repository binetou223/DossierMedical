<?php

declare(strict_types=1);

namespace App\DTOs\Consultation;

use App\DTOs\Contracts\DataTransferObjectInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class CreateConsultationDTO implements DataTransferObjectInterface
{
    public function __construct(
        public int $dossierMedicalId,
        public int $medecinId,
        public ?int $rendezVousId = null,
        public ?Carbon $dateHeure = null,
        public string $motifConsultation = '',
        public ?string $histoireMaladie = null,
        public ?string $examenPhysique = null,
        public string $diagnostic = '',
        public ?string $conduiteATenir = null,
        public bool $estCloturee = false,
        public ?Carbon $clotureeLe = null,
    ) {}

    public static function builder(): CreateConsultationDTOBuilder
    {
        return CreateConsultationDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            dossierMedicalId: (int) $data['dossier_medical_id'],
            medecinId: (int) $data['medecin_id'],
            rendezVousId: isset($data['rendez_vous_id']) ? (int) $data['rendez_vous_id'] : null,
            dateHeure: ! empty($data['date_heure']) ? Carbon::parse($data['date_heure']) : Carbon::now(),
            motifConsultation: (string) ($data['motif_consultation'] ?? ''),
            histoireMaladie: $data['histoire_maladie'] ?? null,
            examenPhysique: $data['examen_physique'] ?? null,
            diagnostic: (string) ($data['diagnostic'] ?? ''),
            conduiteATenir: $data['conduite_a_tenir'] ?? null,
            estCloturee: ! empty($data['est_cloturee']),
            clotureeLe: ! empty($data['cloturee_le']) ? Carbon::parse($data['cloturee_le']) : null,
        );
    }

    public function toArray(): array
    {
        return [
            'dossier_medical_id' => $this->dossierMedicalId,
            'medecin_id' => $this->medecinId,
            'rendez_vous_id' => $this->rendezVousId,
            'date_heure' => ($this->dateHeure ?? Carbon::now())->toDateTimeString(),
            'motif_consultation' => $this->motifConsultation,
            'histoire_maladie' => $this->histoireMaladie,
            'examen_physique' => $this->examenPhysique,
            'diagnostic' => $this->diagnostic,
            'conduite_a_tenir' => $this->conduiteATenir,
            'est_cloturee' => $this->estCloturee,
            'cloturee_le' => $this->clotureeLe?->toDateTimeString(),
        ];
    }
}
