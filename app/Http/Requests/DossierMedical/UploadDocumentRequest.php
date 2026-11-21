<?php

declare(strict_types=1);

namespace App\Http\Requests\DossierMedical;

use App\DTOs\DocumentMedical\UploadDocumentMedicalDTO;
use Illuminate\Foundation\Http\FormRequest;

class UploadDocumentRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:255'],
            'type_document' => ['required', 'string', 'max:100'],
            'fichier' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'], // 10MB max
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function toDTO(): UploadDocumentMedicalDTO
    {
        $validated = $this->validated();
        $file = $this->file('fichier');

        $path = $file ? $file->store('documents_medicaux', 'public') : 'documents/default.pdf';

        return new UploadDocumentMedicalDTO(
            dossierMedicalId: (int) $validated['dossier_medical_id'],
            nom: (string) $validated['nom'],
            typeDocument: (string) $validated['type_document'],
            cheminFichier: $path,
            tailleOctets: $file ? $file->getSize() : 0,
            mimeType: $file ? (string) $file->getClientMimeType() : 'application/pdf',
            description: $validated['description'] ?? null
        );
    }
}
