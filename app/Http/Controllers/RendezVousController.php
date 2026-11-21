<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RendezVous\StoreRendezVousRequest;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use App\Services\Contracts\RendezVousServiceInterface;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur allégé (Skinny Controller) pour les rendez-vous médicaux (SRP / DIP).
 */
class RendezVousController extends Controller
{
    public function __construct(
        protected RendezVousServiceInterface $rendezVousService,
    ) {}

    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', RendezVous::class);

        $user = $request->user();

        if ($user->isPatient()) {
            $patient = Patient::where('user_id', $user->id)->firstOrFail();
            $rendezVous = $this->rendezVousService->getHistoryForPatient($patient->id);
            $upcoming = $this->rendezVousService->getUpcomingForPatient($patient->id);
        } elseif ($user->isMedecin()) {
            $medecin = Medecin::where('user_id', $user->id)->firstOrFail();
            $rendezVous = RendezVous::with(['patient.user', 'service'])
                ->where('medecin_id', $medecin->id)
                ->orderByDesc('date_heure_debut')
                ->paginate(15);
            $upcoming = $this->rendezVousService->getDailyScheduleForMedecin($medecin->id);
        } else {
            $rendezVous = RendezVous::with(['patient.user', 'medecin.user', 'service'])
                ->orderByDesc('date_heure_debut')
                ->paginate(15);
            $upcoming = [];
        }

        return Inertia::render('RendezVous/Index', [
            'rendezVous' => $rendezVous,
            'upcoming' => $upcoming,
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', RendezVous::class);

        return Inertia::render('RendezVous/Create', [
            'services' => Service::all(['id', 'nom', 'code']),
            'medecins' => Medecin::with('user:id,name')->get(),
        ]);
    }

    public function store(StoreRendezVousRequest $request): RedirectResponse
    {
        Gate::authorize('create', RendezVous::class);

        try {
            $rdv = $this->rendezVousService->scheduleAppointment($request->toDTO());

            return redirect()->route('rendez-vous.index')
                ->with('success', 'Rendez-vous planifié avec succès.');
        } catch (DomainException $e) {
            return back()->withErrors(['date_heure_debut' => $e->getMessage()]);
        }
    }

    public function confirm(int $id): RedirectResponse
    {
        $rdv = RendezVous::findOrFail($id);
        Gate::authorize('confirm', $rdv);

        try {
            $this->rendezVousService->confirmAppointment($id);

            return back()->with('success', 'Le rendez-vous a été confirmé.');
        } catch (DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function cancel(Request $request, int $id): RedirectResponse
    {
        $rdv = RendezVous::findOrFail($id);
        Gate::authorize('cancel', $rdv);

        $request->validate([
            'motif_annulation' => ['required', 'string', 'max:500'],
        ]);

        try {
            $this->rendezVousService->cancelAppointment($id, (string) $request->input('motif_annulation'));

            return back()->with('success', 'Le rendez-vous a été annulé.');
        } catch (DomainException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
