<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Constante\CreateConstanteDTO;
use App\DTOs\DocumentMedical\UploadDocumentMedicalDTO;
use App\DTOs\DossierMedical\UpdateDossierMedicalDTO;
use App\Enums\AuditAction;
use App\Models\DocumentMedical;
use App\Models\DossierMedical;
use App\Repositories\Contracts\DossierMedicalRepositoryInterface;
use App\Services\Contracts\AuditServiceInterface;
use App\Services\Contracts\DossierMedicalServiceInterface;
use App\Services\Strategies\VitalsAlertManager;

/**
 * Service Métier pour le Dossier Médical et l'analyse clinique des constantes (SRP, DIP & Strategy Pattern).
 */
class DossierMedicalService implements DossierMedicalServiceInterface
{
    public function __construct(
        protected DossierMedicalRepositoryInterface $dossierRepository,
        protected VitalsAlertManager $vitalsAlertManager,
        protected AuditServiceInterface $auditService,
    ) {}

    public function getDossierByPatient(int $patientId): ?DossierMedical
    {
        $dossier = $this->dossierRepository->findByPatientId($patientId);

        if ($dossier !== null && auth()->check()) {
            $this->auditService->logAccess(
                userId: auth()->id(),
                action: AuditAction::CONSULTATION_DOSSIER,
                entiteType: 'DossierMedical',
                entiteId: $dossier->id,
                description: "Consultation du dossier médical du patient n°{$patientId}"
            );
        }

        return $dossier;
    }

    public function updateDossier(int $id, UpdateDossierMedicalDTO $dto): DossierMedical
    {
        $data = array_filter([
            'antecedents_personnels' => $dto->antecedentsPersonnels,
            'antecedents_chirurgicaux' => $dto->antecedentsChirurgicaux,
            'antecedents_familiaux' => $dto->antecedentsFamiliaux,
            'allergies' => $dto->allergies,
            'traitements_chroniques' => $dto->traitementsChroniques,
            'regime_alimentaire' => $dto->regimeAlimentaire,
            'notes_confidentielles' => $dto->notesConfidentielles,
        ], fn ($val) => $val !== null);

        $dossier = $this->dossierRepository->update($id, $data);

        $this->auditService->logAccess(
            userId: auth()->id() ?? 1,
            action: AuditAction::MODIFICATION_DOSSIER,
            entiteType: 'DossierMedical',
            entiteId: $dossier->id,
            description: "Mise à jour des antécédents et informations cliniques du dossier n°{$dossier->id}"
        );

        return $dossier;
    }

    public function recordConstantes(CreateConstanteDTO $dto): array
    {
        $poids = $dto->poidsKg;
        $taille = $dto->tailleCm;
        $imc = $dto->imc;

        // Calcul automatique de l'IMC si le poids et la taille sont renseignés
        if ($imc === null && $poids !== null && $taille !== null) {
            $tailleM = $taille / 100.0;
            if ($tailleM > 0) {
                $imc = round($poids / ($tailleM * $tailleM), 1);
            }
        }

        $constanteData = [
            'prise_par_user_id' => $dto->priseParUserId ?? auth()->id(),
            'date_mesure' => $dto->dateMesure ?? now(),
            'poids_kg' => $poids,
            'taille_cm' => $taille,
            'tension_systolique' => $dto->tensionSystolique,
            'tension_diastolique' => $dto->tensionDiastolique,
            'frequence_cardiaque' => $dto->frequenceCardiaque,
            'temperature_celsius' => $dto->temperatureCelsius,
            'glycemie_g_l' => $dto->glycemieGL,
            'imc' => $imc,
            'observations' => $dto->observations,
        ];

        $constante = $this->dossierRepository->addConstante($dto->dossierMedicalId, $constanteData);

        // Pattern Strategy : Évaluation de toutes les alertes de santé enregistrées
        $alertes = $this->vitalsAlertManager->evaluateAll($constante);

        return [
            'constante' => $constante,
            'alertes' => $alertes,
        ];
    }

    public function attachDocument(UploadDocumentMedicalDTO $dto): DocumentMedical
    {
        return $this->dossierRepository->addDocument($dto->dossierMedicalId, [
            'nom' => $dto->nom,
            'type_document' => $dto->typeDocument,
            'chemin_fichier' => $dto->cheminFichier,
            'taille_octets' => $dto->tailleOctets,
            'mime_type' => $dto->mimeType,
            'description' => $dto->description,
        ]);
    }
}
