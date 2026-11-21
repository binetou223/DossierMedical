<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AuditAction;
use App\Http\Requests\Consultation\StoreConsultationRequest;
use App\Models\Consultation;
use App\Models\DossierMedical;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Services\Contracts\AuditServiceInterface;
use App\Services\Contracts\ConsultationServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur allégé pour la gestion des consultations et prescriptions (SRP / DIP).
 */
class ConsultationController extends Controller
{
    public function __construct(
        protected ConsultationServiceInterface $consultationService,
        protected AuditServiceInterface $auditService,
    ) {}

    public function create(Request $request): Response
    {
        Gate::authorize('create', Consultation::class);

        $dossierId = (int) $request->query('dossier_id');
        $rdvId = $request->query('rendez_vous_id') ? (int) $request->query('rendez_vous_id') : null;

        $dossier = DossierMedical::with(['patient.user', 'constantes' => fn ($q) => $q->latest()->limit(1)])
            ->findOrFail($dossierId);

        $medecin = Medecin::where('user_id', $request->user()->id)->firstOrFail();
        $rendezVous = $rdvId ? RendezVous::find($rdvId) : null;

        return Inertia::render('Consultations/Create', [
            'dossier' => $dossier,
            'medecin' => $medecin,
            'rendezVous' => $rendezVous,
        ]);
    }

    public function store(StoreConsultationRequest $request): RedirectResponse
    {
        Gate::authorize('create', Consultation::class);

        $consultation = $this->consultationService->conductConsultation(
            $request->toConsultationDTO(),
            $request->toOrdonnanceDTO()
        );

        return redirect()->route('consultations.show', $consultation->id)
            ->with('success', 'Consultation enregistrée avec succès.');
    }

    public function show(int $id): Response
    {
        $consultation = Consultation::with([
            'medecin.user',
            'dossierMedical.patient.user',
            'ordonnance.prescriptions',
            'rendezVous',
        ])->findOrFail($id);

        Gate::authorize('view', $consultation);

        return Inertia::render('Consultations/Show', [
            'consultation' => $consultation,
        ]);
    }

    public function cloturer(int $id): RedirectResponse
    {
        $consultation = Consultation::findOrFail($id);
        Gate::authorize('cloturer', $consultation);

        $this->consultationService->closeConsultation($id);

        return back()->with('success', 'La consultation a été clôturée définitivement.');
    }

    public function printOrdonnance(int $id): Response
    {
        $consultation = Consultation::with([
            'medecin.user',
            'medecin.service',
            'dossierMedical.patient.user',
            'ordonnance.prescriptions',
        ])->findOrFail($id);

        Gate::authorize('view', $consultation);

        // Journalisation de l'impression de l'ordonnance (CDP)
        $this->auditService->logAccess(
            userId: auth()->id(),
            action: AuditAction::IMPRESSION_ORDONNANCE,
            entiteType: 'Ordonnance',
            entiteId: $consultation->ordonnance?->id ?? 0,
            description: "Impression / téléchargement de l'ordonnance pour le patient {$consultation->dossierMedical->patient->user->name}"
        );

        return Inertia::render('Consultations/PrintOrdonnance', [
            'consultation' => $consultation,
        ]);
    }
}
