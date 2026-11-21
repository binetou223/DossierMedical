<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\ConsultationRepositoryInterface;
use App\Repositories\Contracts\DossierMedicalRepositoryInterface;
use App\Repositories\Contracts\JournalAuditRepositoryInterface;
use App\Repositories\Contracts\MedecinRepositoryInterface;
use App\Repositories\Contracts\PatientRepositoryInterface;
use App\Repositories\Contracts\RendezVousRepositoryInterface;
use App\Repositories\Eloquent\EloquentConsultationRepository;
use App\Repositories\Eloquent\EloquentDossierMedicalRepository;
use App\Repositories\Eloquent\EloquentJournalAuditRepository;
use App\Repositories\Eloquent\EloquentMedecinRepository;
use App\Repositories\Eloquent\EloquentPatientRepository;
use App\Repositories\Eloquent\EloquentRendezVousRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider enregistrant les liaisons d'interfaces vers les implémentations concrètes
 * pour respecter le principe d'inversion des dépendances (SOLID - DIP).
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Liaisons de conteneur d'injection de dépendances.
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        PatientRepositoryInterface::class => EloquentPatientRepository::class,
        RendezVousRepositoryInterface::class => EloquentRendezVousRepository::class,
        DossierMedicalRepositoryInterface::class => EloquentDossierMedicalRepository::class,
        ConsultationRepositoryInterface::class => EloquentConsultationRepository::class,
        JournalAuditRepositoryInterface::class => EloquentJournalAuditRepository::class,
        MedecinRepositoryInterface::class => EloquentMedecinRepository::class,
    ];

    public function register(): void
    {
        // Les bindings sont automatiquement résolus par Laravel
    }

    public function boot(): void
    {
        //
    }
}
