<?php

declare(strict_types=1);

namespace App\DTOs\RendezVous;

use App\DTOs\Contracts\DataTransferObjectInterface;
use App\Enums\AppointmentStatus;
use Illuminate\Http\Request;

readonly class UpdateRendezVousStatusDTO implements DataTransferObjectInterface
{
    public function __construct(
        public AppointmentStatus $statut,
        public ?string $motifAnnulation = null,
        public ?string $notesInternes = null,
    ) {}

    public static function builder(): UpdateRendezVousStatusDTOBuilder
    {
        return UpdateRendezVousStatusDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $statut = $data['statut'] instanceof AppointmentStatus
            ? $data['statut']
            : AppointmentStatus::from((string) $data['statut']);

        return new self(
            statut: $statut,
            motifAnnulation: $data['motif_annulation'] ?? null,
            notesInternes: $data['notes_internes'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'statut' => $this->statut->value,
            'motif_annulation' => $this->motifAnnulation,
            'notes_internes' => $this->notesInternes,
        ];
    }
}
