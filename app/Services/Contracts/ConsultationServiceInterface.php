<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\DTOs\Consultation\CreateConsultationDTO;
use App\DTOs\Ordonnance\CreateOrdonnanceDTO;
use App\Models\Consultation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contrat du Service des Consultations et Prescriptions (SRP / DIP).
 */
interface ConsultationServiceInterface
{
    /**
     * Enregistre une consultation médicale avec éventuellement son ordonnance et ses prescriptions.
     */
    public function conductConsultation(CreateConsultationDTO $consultationDTO, ?CreateOrdonnanceDTO $ordonnanceDTO = null): Consultation;

    /**
     * Clôture définitivement une consultation médicale.
     */
    public function closeConsultation(int $consultationId): Consultation;

    /**
     * Historique paginé des consultations d'un dossier patient.
     *
     * @return LengthAwarePaginator<Consultation>
     */
    public function getConsultationsByDossier(int $dossierId, int $perPage = 15): LengthAwarePaginator;

    /**
     * Consultations menées par un praticien.
     *
     * @return LengthAwarePaginator<Consultation>
     */
    public function getConsultationsByMedecin(int $medecinId, int $perPage = 15): LengthAwarePaginator;
}
