<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Secretaire;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur pour la gestion des utilisateurs et des rôles par l'administrateur.
 */
class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $search = (string) $request->query('search', '');
        $role = $request->query('role');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone', 'like', "%{$search}%");
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'services' => Service::all(['id', 'nom']),
            'filters' => [
                'search' => $search,
                'role' => $role,
            ],
        ]);
    }

    public function toggleActive(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Protection : l'administrateur ne peut pas désactiver son propre compte
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Vous ne pouvez pas désactiver votre propre compte administrateur.']);
        }

        $user->update(['is_active' => ! $user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Le compte de {$user->name} a été {$status}.");
    }

    public function updateRole(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => ['required', 'string'],
            'service_id' => ['nullable', 'integer', 'exists:services,id'],
            'specialite' => ['nullable', 'string', 'max:150'],
            'matricule' => ['nullable', 'string', 'max:50'],
        ]);

        $role = UserRole::from((string) $request->input('role'));
        $user->update(['role' => $role]);

        // Si le rôle devient Médecin, assurer la création du profil praticien
        if ($role === UserRole::MEDECIN && ! $user->medecin) {
            Medecin::create([
                'user_id' => $user->id,
                'service_id' => $request->input('service_id', 1),
                'specialite' => $request->input('specialite', 'Médecine générale'),
                'matricule' => $request->input('matricule', 'MED-'.date('Y').'-'.$user->id),
            ]);
        }

        // Si le rôle devient Secrétaire, assurer la création du profil
        if ($role === UserRole::SECRETAIRE && ! $user->secretaire) {
            Secretaire::create([
                'user_id' => $user->id,
                'service_id' => $request->input('service_id', 1),
                'matricule' => 'SEC-'.date('Y').'-'.$user->id,
            ]);
        }

        return back()->with('success', "Rôle mis à jour pour l'utilisateur {$user->name}.");
    }
}
