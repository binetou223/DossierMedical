<?php

declare(strict_types=1);

namespace App\Http\Requests\Patient;

use App\DTOs\Patient\UpdatePatientDTO;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'genre' => ['nullable', 'in:M,F'],
            'groupe_sanguin' => ['nullable', 'string'],
            'profession' => ['nullable', 'string', 'max:150'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:150'],
            'contact_urgence_tel' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function toDTO(): UpdatePatientDTO
    {
        return UpdatePatientDTO::fromArray($this->validated());
    }
}
