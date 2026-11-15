<?php

declare(strict_types=1);

namespace App\DTOs\DocumentMedical;

use App\DTOs\Contracts\BuilderInterface;
use InvalidArgumentException;

class UploadDocumentMedicalDTOBuilder implements BuilderInterface
{
    private ?int $dossierMedicalId = null;

    private string $nom = '';

    private string $typeDocument = '';

    private string $cheminFichier = '';

    private ?int $tailleOctets = null;

    private ?int $ajouteParUserId = null;

    public static function create(): self
    {
        return new self;
    }

    public function forDossier(int $dossierMedicalId): self
    {
        $this->dossierMedicalId = $dossierMedicalId;

        return $this;
    }

    public function withNom(string $nom): self
    {
        $this->nom = trim($nom);

        return $this;
    }

    public function ofType(string $typeDocument): self
    {
        $this->typeDocument = trim($typeDocument);

        return $this;
    }

    public function storedAt(string $cheminFichier): self
    {
        $this->cheminFichier = trim($cheminFichier);

        return $this;
    }

    public function withSize(?int $tailleOctets): self
    {
        $this->tailleOctets = $tailleOctets;

        return $this;
    }

    public function uploadedBy(?int $userId): self
    {
        $this->ajouteParUserId = $userId;

        return $this;
    }

    public function build(): UploadDocumentMedicalDTO
    {
        if ($this->dossierMedicalId === null) {
            throw new InvalidArgumentException('Le dossierMedicalId est obligatoire.');
        }

        if (empty($this->nom)) {
            throw new InvalidArgumentException('Le nom du document est obligatoire.');
        }

        if (empty($this->typeDocument)) {
            throw new InvalidArgumentException('Le type du document est obligatoire.');
        }

        if (empty($this->cheminFichier)) {
            throw new InvalidArgumentException('Le chemin du fichier est obligatoire.');
        }

        return new UploadDocumentMedicalDTO(
            dossierMedicalId: $this->dossierMedicalId,
            nom: $this->nom,
            typeDocument: $this->typeDocument,
            cheminFichier: $this->cheminFichier,
            tailleOctets: $this->tailleOctets,
            ajouteParUserId: $this->ajouteParUserId,
        );
    }
}
