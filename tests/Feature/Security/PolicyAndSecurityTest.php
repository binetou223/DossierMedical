<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\DossierMedical;
use App\Models\JournalAudit;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

test('DossierMedicalPolicy garantit le secret medical : un patient ne peut voir que son dossier', function () {
    $pat1User = User::create(['name' => 'Patient 1', 'email' => 'p1@dme.sn', 'password' => 'secret', 'role' => UserRole::PATIENT]);
    $patient1 = Patient::create(['user_id' => $pat1User->id, 'numero_dossier' => 'PAT-001', 'genre' => 'M']);
    $dossier1 = DossierMedical::create(['patient_id' => $patient1->id]);

    $pat2User = User::create(['name' => 'Patient 2', 'email' => 'p2@dme.sn', 'password' => 'secret', 'role' => UserRole::PATIENT]);
    $patient2 = Patient::create(['user_id' => $pat2User->id, 'numero_dossier' => 'PAT-002', 'genre' => 'F']);
    $dossier2 = DossierMedical::create(['patient_id' => $patient2->id]);

    // Patient 1 peut voir son propre dossier
    expect(Gate::forUser($pat1User)->allows('view', $dossier1))->toBeTrue();

    // Patient 1 NE PEUT PAS voir le dossier de Patient 2 (Secret Médical)
    expect(Gate::forUser($pat1User)->allows('view', $dossier2))->toBeFalse();
});

test('EnsureUserHasRole middleware interdit l acces aux routes administratives pour les non-admins et journalise l audit', function () {
    $patUser = User::factory()->create([
        'name' => 'Patient Simple',
        'email' => 'simple@dme.sn',
        'role' => UserRole::PATIENT,
        'is_active' => true,
    ]);

    $response = $this->actingAs($patUser)->get('/admin/dashboard');

    $response->assertForbidden();

    // Verifier la trace d'audit de la violation d'acces
    $violations = JournalAudit::where('entite_type', 'AccessViolation')->get();
    expect($violations)->not->toBeEmpty();
});
