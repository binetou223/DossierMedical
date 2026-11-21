<?php

declare(strict_types=1);

namespace App\Http\Requests\DossierMedical;

use App\DTOs\DossierMedical\UpdateDossierMedicalDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDossierMedicalRequest extends FormRequest
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
            'antecedents_personnels' => ['nullable', 'string'],
            'antecedents_chirurgicaux' => ['nullable', 'string'],
            'antecedents_familiaux' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'traitements_chroniques' => ['nullable', 'string'],
            'regime_alimentaire' => ['nullable', 'string'],
            'notes_confidentielles' => ['nullable', 'string'],
        ];
    }

    public function toDTO(): UpdateDossierMedicalDTO
    {
        return UpdateDossierMedicalDTO::fromArray($this->validated());
    }
}
