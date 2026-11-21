<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Medecin;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contrat de persistance pour les praticiens et spécialités médicales (ISP / DIP).
 *
 * @extends BaseRepositoryInterface<Medecin>
 */
interface MedecinRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Liste des médecins d'un service hospitalier.
     *
     * @return Collection<int, Medecin>
     */
    public function findByService(int $serviceId): Collection;

    /**
     * Profil médecin lié à un compte utilisateur.
     */
    public function findByUserId(int $userId): ?Medecin;

    /**
     * Médecins exerçant une spécialité donnée.
     *
     * @return Collection<int, Medecin>
     */
    public function findBySpecialite(string $specialite): Collection;

    /**
     * Tous les médecins actifs avec leurs relations (User, Service).
     *
     * @return Collection<int, Medecin>
     */
    public function allWithDetails(): Collection;
}
