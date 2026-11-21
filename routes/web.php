<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DossierMedicalController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RendezVousController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isPatient()) {
            return redirect()->route('dossiers.mon-dossier');
        }

        return redirect()->route('rendez-vous.index');
    })->name('dashboard');

    // Module Patients
    Route::resource('patients', PatientController::class);

    // Module Rendez-vous
    Route::resource('rendez-vous', RendezVousController::class);
    Route::patch('rendez-vous/{id}/confirm', [RendezVousController::class, 'confirm'])->name('rendez-vous.confirm');
    Route::patch('rendez-vous/{id}/cancel', [RendezVousController::class, 'cancel'])->name('rendez-vous.cancel');

    // Module Dossier Médical
    Route::get('mon-dossier', [DossierMedicalController::class, 'monDossier'])->name('dossiers.mon-dossier');
    Route::get('dossiers/{id}', [DossierMedicalController::class, 'show'])->name('dossiers.show');
    Route::put('dossiers/{id}', [DossierMedicalController::class, 'update'])->name('dossiers.update');
    Route::post('dossiers/constantes', [DossierMedicalController::class, 'storeConstante'])->name('dossiers.constantes.store');
    Route::post('dossiers/documents', [DossierMedicalController::class, 'uploadDocument'])->name('dossiers.documents.store');

    // Module Consultations & Ordonnances
    Route::get('consultations/create', [ConsultationController::class, 'create'])->name('consultations.create');
    Route::post('consultations', [ConsultationController::class, 'store'])->name('consultations.store');
    Route::get('consultations/{id}', [ConsultationController::class, 'show'])->name('consultations.show');
    Route::patch('consultations/{id}/cloturer', [ConsultationController::class, 'cloturer'])->name('consultations.cloturer');
    Route::get('consultations/{id}/ordonnance', [ConsultationController::class, 'printOrdonnance'])->name('consultations.ordonnance.print');

    // Module Administration (réservé aux administrateurs)
    Route::prefix('admin')->middleware(['role:admin'])->name('admin.')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('audit', [AuditController::class, 'index'])->name('audit.index');
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::patch('users/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::patch('users/{id}/role', [UserController::class, 'updateRole'])->name('users.update-role');
    });
});

require __DIR__.'/settings.php';
