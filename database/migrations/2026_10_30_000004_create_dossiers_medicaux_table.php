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
        // Table des dossiers médicaux
        Schema::create('dossiers_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->unique()->constrained('patients')->cascadeOnDelete();
            $table->text('antecedents_personnels')->nullable();
            $table->text('antecedents_chirurgicaux')->nullable();
            $table->text('antecedents_familiaux')->nullable();
            $table->text('allergies')->nullable();
            $table->text('traitements_chroniques')->nullable();
            $table->text('regime_alimentaire')->nullable();
            $table->text('notes_confidentielles')->nullable();
            $table->timestamps();
        });

        // Table des constantes vitales
        Schema::create('constantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_medical_id')->constrained('dossiers_medicaux')->cascadeOnDelete();
            $table->foreignId('prise_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_mesure')->index();
            $table->decimal('poids_kg', 5, 2)->nullable();
            $table->decimal('taille_cm', 5, 2)->nullable();
            $table->integer('tension_systolique')->nullable();
            $table->integer('tension_diastolique')->nullable();
            $table->integer('frequence_cardiaque')->nullable();
            $table->decimal('temperature_celsius', 4, 1)->nullable();
            $table->decimal('glycemie_g_l', 4, 2)->nullable();
            $table->decimal('imc', 4, 1)->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        // Table des documents médicaux
        Schema::create('documents_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_medical_id')->constrained('dossiers_medicaux')->cascadeOnDelete();
            $table->string('nom');
            $table->string('type_document');
            $table->string('chemin_fichier');
            $table->unsignedBigInteger('taille_octets')->nullable();
            $table->foreignId('ajoute_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_medicaux');
        Schema::dropIfExists('constantes');
        Schema::dropIfExists('dossiers_medicaux');
    }
};
