<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Service;
use App\Models\User;
use App\Services\Contracts\AuditServiceInterface;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Contrôleur d'administration pour les statistiques et indicateurs de pilotage (Module 6 du mémoire).
 */
class AdminDashboardController extends Controller
{
    public function __construct(
        protected AuditServiceInterface $auditService,
    ) {}

    public function index(): Response
    {
        $stats = [
            'total_patients' => Patient::count(),
            'total_medecins' => Medecin::count(),
            'total_consultations' => Consultation::count(),
            'total_services' => Service::count(),
            'rdv_en_attente' => RendezVous::where('statut', AppointmentStatus::EN_ATTENTE)->count(),
            'rdv_confirmes' => RendezVous::where('statut', AppointmentStatus::CONFIRME)->count(),
            'rdv_termines' => RendezVous::where('statut', AppointmentStatus::TERMINE)->count(),
            'utilisateurs_actifs' => User::where('is_active', true)->count(),
        ];

        $repartitionParRole = [
            'patients' => User::where('role', UserRole::PATIENT)->count(),
            'medecins' => User::where('role', UserRole::MEDECIN)->count(),
            'secretaires' => User::where('role', UserRole::SECRETAIRE)->count(),
            'admins' => User::where('role', UserRole::ADMIN)->count(),
        ];

        $recentAudits = $this->auditService->getRecentAuditLogs(10);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'repartition' => $repartitionParRole,
            'recentAudits' => $recentAudits,
        ]);
    }
}
