<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AuditService;
use App\Services\ConsultationService;
use App\Services\Contracts\AuditServiceInterface;
use App\Services\Contracts\ConsultationServiceInterface;
use App\Services\Contracts\DossierMedicalServiceInterface;
use App\Services\Contracts\PatientServiceInterface;
use App\Services\Contracts\RendezVousServiceInterface;
use App\Services\DossierMedicalService;
use App\Services\PatientService;
use App\Services\RendezVousService;
use App\Services\Strategies\VitalsAlertManager;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider pour la couche métier (Service Layer & Strategy Pattern).
 * Respecte le principe d'inversion des dépendances (SOLID - DIP).
 */
class BusinessServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        AuditServiceInterface::class => AuditService::class,
        PatientServiceInterface::class => PatientService::class,
        RendezVousServiceInterface::class => RendezVousService::class,
        DossierMedicalServiceInterface::class => DossierMedicalService::class,
        ConsultationServiceInterface::class => ConsultationService::class,
    ];

    public function register(): void
    {
        $this->app->singleton(VitalsAlertManager::class, function () {
            return new VitalsAlertManager;
        });
    }

    public function boot(): void
    {
        //
    }
}
