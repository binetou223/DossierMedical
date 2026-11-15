<?php

declare(strict_types=1);

namespace App\DTOs\DocumentMedical;

use App\DTOs\Contracts\DataTransferObjectInterface;
use Illuminate\Http\Request;

readonly class UploadDocumentMedicalDTO implements DataTransferObjectInterface
{
    public function __construct(
        public int $dossierMedicalId,
        public string $nom,
        public string $typeDocument,
        public string $cheminFichier,
        public ?int $tailleOctets = null,
        public ?int $ajouteParUserId = null,
    ) {}

    public static function builder(): UploadDocumentMedicalDTOBuilder
    {
        return UploadDocumentMedicalDTOBuilder::create();
    }

    public static function fromRequest(Request $request): self
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        return self::fromArray([
            ...$data,
            'ajoute_par_user_id' => $request->user()?->id,
        ]);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            dossierMedicalId: (int) $data['dossier_medical_id'],
            nom: (string) ($data['nom'] ?? ''),
            typeDocument: (string) ($data['type_document'] ?? ''),
            cheminFichier: (string) ($data['chemin_fichier'] ?? ''),
            tailleOctets: isset($data['taille_octets']) ? (int) $data['taille_octets'] : null,
            ajouteParUserId: isset($data['ajoute_par_user_id']) ? (int) $data['ajoute_par_user_id'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'dossier_medical_id' => $this->dossierMedicalId,
            'nom' => $this->nom,
            'type_document' => $this->typeDocument,
            'chemin_fichier' => $this->cheminFichier,
            'taille_octets' => $this->tailleOctets,
            'ajoute_par_user_id' => $this->ajouteParUserId,
        ];
    }
}
