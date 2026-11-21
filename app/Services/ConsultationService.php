<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\Consultation\CreateConsultationDTO;
use App\DTOs\Ordonnance\CreateOrdonnanceDTO;
use App\Enums\AuditAction;
use App\Models\Consultation;
use App\Repositories\Contracts\ConsultationRepositoryInterface;
use App\Services\Contracts\AuditServiceInterface;
use App\Services\Contracts\ConsultationServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Service Métier pour les consultations et ordonnances médicales (SRP & DIP).
 */
class ConsultationService implements ConsultationServiceInterface
{
    public function __construct(
        protected ConsultationRepositoryInterface $consultationRepository,
        protected AuditServiceInterface $auditService,
    ) {}

    public function conductConsultation(
        CreateConsultationDTO $consultationDTO,
        ?CreateOrdonnanceDTO $ordonnanceDTO = null
    ): Consultation {
        $consultationData = [
            'dossier_medical_id' => $consultationDTO->dossierMedicalId,
            'medecin_id' => $consultationDTO->medecinId,
            'rendez_vous_id' => $consultationDTO->rendezVousId,
            'date_heure' => $consultationDTO->dateHeure ?? now(),
            'motif_consultation' => $consultationDTO->motifConsultation,
            'histoire_maladie' => $consultationDTO->histoireMaladie,
            'examen_physique' => $consultationDTO->examenPhysique,
            'diagnostic' => $consultationDTO->diagnostic,
            'conduite_a_tenir' => $consultationDTO->conduiteATenir,
            'est_cloturee' => $consultationDTO->estCloturee,
            'cloturee_le' => $consultationDTO->clotureeLe,
        ];

        $ordonnanceData = null;
        $prescriptions = [];

        if ($ordonnanceDTO !== null) {
            $ordonnanceData = [
                'instructions_generales' => $ordonnanceDTO->instructionsGenerales ?? $ordonnanceDTO->instructions ?? null,
                'date_ordonnance' => $ordonnanceDTO->dateOrdonnance?->toDateString() ?? now()->toDateString(),
            ];

            foreach ($ordonnanceDTO->items as $item) {
                $prescriptions[] = [
                    'nom_medicament' => $item->nomMedicament,
                    'dosage' => $item->dosage,
                    'forme' => $item->forme,
                    'posologie' => $item->posologie ?? $item->frequence ?? 'Selon prescription médicale',
                    'duree' => $item->duree ?? ($item->dureeJours ? "{$item->dureeJours} jours" : 'Durée standard'),
                    'instructions' => $item->instructions ?? $item->instructionsParticulieres ?? null,
                ];
            }
        }

        $consultation = $this->consultationRepository->createWithOrdonnance(
            $consultationData,
            $ordonnanceData,
            $prescriptions
        );

        $this->auditService->logAccess(
            userId: auth()->id() ?? $consultation->medecin->user_id,
            action: AuditAction::ENREGISTREMENT_CONSULTATION,
            entiteType: 'Consultation',
            entiteId: $consultation->id,
            description: "Enregistrement de consultation médicale pour le dossier n°{$consultation->dossier_medical_id}",
            details: [
                'diagnostic' => $consultation->diagnostic,
                'a_ordonnance' => $ordonnanceDTO !== null,
            ]
        );

        return $consultation;
    }

    public function closeConsultation(int $consultationId): Consultation
    {
        $consultation = $this->consultationRepository->cloturer($consultationId);

        $this->auditService->logAccess(
            userId: auth()->id() ?? $consultation->medecin->user_id,
            action: AuditAction::CLOTURE_CONSULTATION,
            entiteType: 'Consultation',
            entiteId: $consultation->id,
            description: "Clôture définitive de la consultation n°{$consultation->id}"
        );

        return $consultation;
    }

    public function getConsultationsByDossier(int $dossierId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->consultationRepository->findByDossierMedical($dossierId, $perPage);
    }

    public function getConsultationsByMedecin(int $medecinId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->consultationRepository->findByMedecin($medecinId, $perPage);
    }
}
