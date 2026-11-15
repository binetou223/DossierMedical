<?php

declare(strict_types=1);

namespace App\DTOs\RendezVous;

use App\DTOs\Contracts\DataTransferObjectInterface;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

readonly class CreateRendezVousDTO implements DataTransferObjectInterface
{
    public function __construct(
        public int $patientId,
        public int $medecinId,
        public int $serviceId,
        public Carbon $dateHeureDebut,
        public Carbon $dateHeureFin,
        public string $motif,
        public AppointmentStatus $statut = AppointmentStatus::EN_ATTENTE,
        public ?string $notesInternes = null,
    ) {}

    public static function builder(): CreateRendezVousDTOBuilder
    {
        return CreateRendezVousDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $debut = Carbon::parse($data['date_heure_debut']);
        $fin = isset($data['date_heure_fin'])
            ? Carbon::parse($data['date_heure_fin'])
            : (clone $debut)->addMinutes(30);

        $statut = AppointmentStatus::EN_ATTENTE;
        if (! empty($data['statut'])) {
            $statut = $data['statut'] instanceof AppointmentStatus
                ? $data['statut']
                : AppointmentStatus::from((string) $data['statut']);
        }

        return new self(
            patientId: (int) $data['patient_id'],
            medecinId: (int) $data['medecin_id'],
            serviceId: (int) $data['service_id'],
            dateHeureDebut: $debut,
            dateHeureFin: $fin,
            motif: (string) ($data['motif'] ?? ''),
            statut: $statut,
            notesInternes: $data['notes_internes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'patient_id' => $this->patientId,
            'medecin_id' => $this->medecinId,
            'service_id' => $this->serviceId,
            'date_heure_debut' => $this->dateHeureDebut->toDateTimeString(),
            'date_heure_fin' => $this->dateHeureFin->toDateTimeString(),
            'motif' => $this->motif,
            'statut' => $this->statut->value,
            'notes_internes' => $this->notesInternes,
        ];
    }
}
