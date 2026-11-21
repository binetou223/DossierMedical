<?php

declare(strict_types=1);

namespace App\Http\Requests\DossierMedical;

use App\DTOs\Constante\CreateConstanteDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreConstanteRequest extends FormRequest
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
            'poids_kg' => ['nullable', 'numeric', 'min:0.5', 'max:500'],
            'taille_cm' => ['nullable', 'numeric', 'min:20', 'max:300'],
            'tension_systolique' => ['nullable', 'integer', 'min:40', 'max:300'],
            'tension_diastolique' => ['nullable', 'integer', 'min:20', 'max:200'],
            'frequence_cardiaque' => ['nullable', 'integer', 'min:30', 'max:250'],
            'temperature_celsius' => ['nullable', 'numeric', 'min:30', 'max:45'],
            'glycemie_g_l' => ['nullable', 'numeric', 'min:0.1', 'max:10'],
            'observations' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function toDTO(): CreateConstanteDTO
    {
        $validated = $this->validated();
        $validated['prise_par_user_id'] = $this->user()?->id;

        return CreateConstanteDTO::fromArray($validated);
    }
}
