<?php

declare(strict_types=1);

namespace App\Http\Requests\RendezVous;

use App\DTOs\RendezVous\CreateRendezVousDTO;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreRendezVousRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'medecin_id' => ['required', 'integer', 'exists:medecins,id'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'date_heure_debut' => ['required', 'date', 'after:now'],
            'date_heure_fin' => ['nullable', 'date', 'after:date_heure_debut'],
            'motif' => ['required', 'string', 'max:500'],
            'notes_internes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function toDTO(): CreateRendezVousDTO
    {
        $validated = $this->validated();
        $debut = Carbon::parse($validated['date_heure_debut']);
        $fin = isset($validated['date_heure_fin'])
            ? Carbon::parse($validated['date_heure_fin'])
            : (clone $debut)->addMinutes(30);

        return new CreateRendezVousDTO(
            patientId: (int) $validated['patient_id'],
            medecinId: (int) $validated['medecin_id'],
            serviceId: (int) $validated['service_id'],
            dateHeureDebut: $debut,
            dateHeureFin: $fin,
            motif: (string) $validated['motif'],
            statut: AppointmentStatus::EN_ATTENTE,
            notesInternes: $validated['notes_internes'] ?? null
        );
    }
}
