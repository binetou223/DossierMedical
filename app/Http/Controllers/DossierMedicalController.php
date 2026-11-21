<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DossierMedical\StoreConstanteRequest;
use App\Http\Requests\DossierMedical\UpdateDossierMedicalRequest;
use App\Http\Requests\DossierMedical\UploadDocumentRequest;
use App\Models\DossierMedical;
use App\Models\Patient;
use App\Services\Contracts\DossierMedicalServiceInterface;
use App\Services\Strategies\VitalsAlertManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur allégé pour la gestion du Dossier Médical Électronique (SRP / DIP).
 */
class DossierMedicalController extends Controller
{
    public function __construct(
        protected DossierMedicalServiceInterface $dossierService,
        protected VitalsAlertManager $vitalsAlertManager,
    ) {}

    public function show(int $id): Response
    {
        $dossier = DossierMedical::with([
            'patient.user',
            'constantes' => fn ($q) => $q->latest('date_mesure')->limit(15),
            'documents' => fn ($q) => $q->latest(),
            'consultations' => fn ($q) => $q->with(['medecin.user', 'ordonnance.prescriptions'])->latest('date_heure'),
        ])->findOrFail($id);

        Gate::authorize('view', $dossier);

        // Analyse des alertes sur la dernière prise de constantes
        $derniereConstante = $dossier->constantes->first();
        $alertesDernieresConstantes = $derniereConstante
            ? $this->vitalsAlertManager->evaluateAll($derniereConstante)
            : [];

        return Inertia::render('DossierMedical/Show', [
            'dossier' => $dossier,
            'alertes' => $alertesDernieresConstantes,
        ]);
    }

    public function monDossier(Request $request): RedirectResponse
    {
        $patient = Patient::where('user_id', $request->user()->id)->firstOrFail();
        $dossier = DossierMedical::where('patient_id', $patient->id)->firstOrFail();

        return redirect()->route('dossiers.show', $dossier->id);
    }

    public function update(UpdateDossierMedicalRequest $request, int $id): RedirectResponse
    {
        $dossier = DossierMedical::findOrFail($id);
        Gate::authorize('update', $dossier);

        $this->dossierService->updateDossier($id, $request->toDTO());

        return back()->with('success', 'Dossier médical mis à jour.');
    }

    public function storeConstante(StoreConstanteRequest $request): RedirectResponse
    {
        $dossier = DossierMedical::findOrFail($request->input('dossier_medical_id'));
        Gate::authorize('addConstante', $dossier);

        $result = $this->dossierService->recordConstantes($request->toDTO());

        $hasDanger = collect($result['alertes'])->contains(fn ($a) => ($a['niveau'] ?? '') === 'danger');
        $message = $hasDanger
            ? 'Constantes enregistrées avec alertes cliniques critiques signalées.'
            : 'Constantes enregistrées avec succès.';

        return back()->with('success', $message);
    }

    public function uploadDocument(UploadDocumentRequest $request): RedirectResponse
    {
        $dossier = DossierMedical::findOrFail($request->input('dossier_medical_id'));
        Gate::authorize('uploadDocument', $dossier);

        $this->dossierService->attachDocument($request->toDTO());

        return back()->with('success', 'Document médical attaché au dossier avec succès.');
    }
}
