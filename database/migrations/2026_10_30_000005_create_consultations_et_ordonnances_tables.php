<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table des consultations
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_medical_id')->constrained('dossiers_medicaux')->cascadeOnDelete();
            $table->foreignId('medecin_id')->constrained('medecins')->cascadeOnDelete();
            $table->foreignId('rendez_vous_id')->nullable()->constrained('rendez_vous')->nullOnDelete();
            $table->dateTime('date_heure')->index();
            $table->string('motif_consultation');
            $table->text('histoire_maladie')->nullable();
            $table->text('examen_physique')->nullable();
            $table->text('diagnostic');
            $table->text('conduite_a_tenir')->nullable();
            $table->boolean('est_cloturee')->default(false);
            $table->dateTime('cloturee_le')->nullable();
            $table->timestamps();
        });

        // Table des ordonnances
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->unique()->constrained('consultations')->cascadeOnDelete();
            $table->string('numero_reference')->unique()->index();
            $table->date('date_ordonnance');
            $table->text('instructions_generales')->nullable();
            $table->timestamps();
        });

        // Table des prescriptions de médicaments
        Schema::create('prescriptions_medicaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordonnance_id')->constrained('ordonnances')->cascadeOnDelete();
            $table->string('nom_medicament');
            $table->string('dosage')->nullable();
            $table->string('forme')->nullable();
            $table->string('posologie');
            $table->string('duree');
            $table->text('instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions_medicaments');
        Schema::dropIfExists('ordonnances');
        Schema::dropIfExists('consultations');
    }
};
