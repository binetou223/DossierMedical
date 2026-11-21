<?php

declare(strict_types=1);

namespace App\Http\Requests\Consultation;

use App\DTOs\Consultation\CreateConsultationDTO;
use App\DTOs\Ordonnance\CreateOrdonnanceDTO;
use App\DTOs\Ordonnance\PrescriptionItemDTO;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreConsultationRequest extends FormRequest
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
            'dossier_medical_id' => ['required', 'integer', 'exists:dossiers_medicaux,id'],
            'medecin_id' => ['required', 'integer', 'exists:medecins,id'],
            'rendez_vous_id' => ['nullable', 'integer', 'exists:rendez_vous,id'],
            'date_heure' => ['nullable', 'date'],
            'motif_consultation' => ['required', 'string', 'max:255'],
            'histoire_maladie' => ['nullable', 'string'],
            'examen_physique' => ['nullable', 'string'],
            'diagnostic' => ['required', 'string'],
            'conduite_a_tenir' => ['nullable', 'string'],
            'ordonnance' => ['nullable', 'array'],
            'ordonnance.instructions_generales' => ['nullable', 'string'],
            'ordonnance.prescriptions' => ['nullable', 'array'],
            'ordonnance.prescriptions.*.nom_medicament' => ['required_with:ordonnance.prescriptions', 'string', 'max:255'],
            'ordonnance.prescriptions.*.dosage' => ['nullable', 'string', 'max:100'],
            'ordonnance.prescriptions.*.forme' => ['nullable', 'string', 'max:100'],
            'ordonnance.prescriptions.*.posologie' => ['required_with:ordonnance.prescriptions', 'string', 'max:255'],
            'ordonnance.prescriptions.*.duree' => ['required_with:ordonnance.prescriptions', 'string', 'max:100'],
            'ordonnance.prescriptions.*.instructions' => ['nullable', 'string'],
        ];
    }

    public function toConsultationDTO(): CreateConsultationDTO
    {
        $validated = $this->validated();

        return new CreateConsultationDTO(
            dossierMedicalId: (int) $validated['dossier_medical_id'],
            medecinId: (int) $validated['medecin_id'],
            rendezVousId: isset($validated['rendez_vous_id']) ? (int) $validated['rendez_vous_id'] : null,
            dateHeure: ! empty($validated['date_heure']) ? Carbon::parse($validated['date_heure']) : Carbon::now(),
            motifConsultation: (string) $validated['motif_consultation'],
            histoireMaladie: $validated['histoire_maladie'] ?? null,
            examenPhysique: $validated['examen_physique'] ?? null,
            diagnostic: (string) $validated['diagnostic'],
            conduiteATenir: $validated['conduite_a_tenir'] ?? null
        );
    }

    public function toOrdonnanceDTO(): ?CreateOrdonnanceDTO
    {
        $validated = $this->validated();
        if (empty($validated['ordonnance']) || empty($validated['ordonnance']['prescriptions'])) {
            return null;
        }

        $items = [];
        foreach ($validated['ordonnance']['prescriptions'] as $presc) {
            $items[] = new PrescriptionItemDTO(
                nomMedicament: $presc['nom_medicament'],
                dosage: $presc['dosage'] ?? null,
                forme: $presc['forme'] ?? null,
                posologie: (string) ($presc['posologie'] ?? ''),
                duree: (string) ($presc['duree'] ?? ''),
                instructions: $presc['instructions'] ?? null
            );
        }

        return new CreateOrdonnanceDTO(
            consultationId: null,
            numeroReference: null,
            dateOrdonnance: Carbon::now(),
            instructionsGenerales: $validated['ordonnance']['instructions_generales'] ?? null,
            prescriptions: $items
        );
    }
}
