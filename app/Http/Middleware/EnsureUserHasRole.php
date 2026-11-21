<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\AuditAction;
use App\Enums\UserRole;
use App\Services\Contracts\AuditServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de filtrage d'accès selon les rôles (Chain of Responsibility Pattern).
 * Vérifie l'appartenance de l'utilisateur à un des rôles autorisés et journalise les tentatives d'accès non autorisées.
 */
class EnsureUserHasRole
{
    public function __construct(
        protected AuditServiceInterface $auditService,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Authentification requise.');
        }

        if (! $user->is_active) {
            abort(Response::HTTP_FORBIDDEN, 'Votre compte a été désactivé par l\'administration.');
        }

        // Transformer les arguments string en valeurs d'Enum UserRole
        $allowedRoles = array_map(function (string $roleName) {
            return match (strtolower(trim($roleName))) {
                'admin' => UserRole::ADMIN,
                'medecin' => UserRole::MEDECIN,
                'secretaire' => UserRole::SECRETAIRE,
                'patient' => UserRole::PATIENT,
                default => UserRole::tryFrom($roleName),
            };
        }, $roles);

        if (! in_array($user->role, $allowedRoles, true)) {
            // Traçabilité CDP de la tentative d'accès non autorisée
            $this->auditService->logAccess(
                userId: $user->id,
                action: AuditAction::CONSULTATION_DOSSIER,
                entiteType: 'AccessViolation',
                entiteId: $user->id,
                description: "Tentative d'accès non autorisée à la ressource : {$request->path()} (Rôle: {$user->role->value})",
                ip: $request->ip(),
                userAgent: $request->userAgent()
            );

            abort(Response::HTTP_FORBIDDEN, 'Accès non autorisé pour votre profil.');
        }

        return $next($request);
    }
}
