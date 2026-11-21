<?php

declare(strict_types=1);

namespace App\Http\Requests\Patient;

use App\DTOs\Patient\CreatePatientDTO;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'numero_dossier' => ['nullable', 'string', 'max:50', 'unique:patients,numero_dossier'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'genre' => ['nullable', 'in:M,F'],
            'groupe_sanguin' => ['nullable', 'string'],
            'profession' => ['nullable', 'string', 'max:150'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:150'],
            'contact_urgence_tel' => ['nullable', 'string', 'max:20'],
        ];
    }

    /**
     * Adapter Pattern : conversion directe de la requête validée vers le DTO métier.
     */
    public function toDTO(): CreatePatientDTO
    {
        $validated = $this->validated();

        return CreatePatientDTO::fromArray($validated);
    }
}
