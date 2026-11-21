<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Patient\StorePatientRequest;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Models\Patient;
use App\Services\Contracts\PatientServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur allégé (Skinny Controller) pour la gestion des patients (SRP / DIP).
 */
class PatientController extends Controller
{
    public function __construct(
        protected PatientServiceInterface $patientService,
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Patient::class);

        $search = (string) $request->query('search', '');
        $patients = $this->patientService->searchPatients($search, 15);

        return Inertia::render('Patients/Index', [
            'patients' => $patients,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Patient::class);

        return Inertia::render('Patients/Create');
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        Gate::authorize('create', Patient::class);

        $patient = $this->patientService->registerPatient($request->toDTO());

        return redirect()->route('patients.show', $patient->id)
            ->with('success', 'Dossier patient créé avec succès.');
    }

    public function show(int $id): Response
    {
        $patient = Patient::with(['user', 'dossierMedical'])->findOrFail($id);
        Gate::authorize('view', $patient);

        return Inertia::render('Patients/Show', [
            'patient' => $patient,
        ]);
    }

    public function edit(int $id): Response
    {
        $patient = Patient::with(['user', 'dossierMedical'])->findOrFail($id);
        Gate::authorize('update', $patient);

        return Inertia::render('Patients/Edit', [
            'patient' => $patient,
        ]);
    }

    public function update(UpdatePatientRequest $request, int $id): RedirectResponse
    {
        $patient = Patient::findOrFail($id);
        Gate::authorize('update', $patient);

        $this->patientService->updatePatient($id, $request->toDTO());

        return redirect()->route('patients.show', $id)
            ->with('success', 'Informations du patient mises à jour.');
    }
}
