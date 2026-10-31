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
        // Table des patients
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('numero_dossier')->unique()->index();
            $table->date('date_naissance')->nullable();
            $table->string('genre', 1)->nullable(); // 'M' ou 'F'
            $table->string('groupe_sanguin', 10)->nullable();
            $table->string('profession')->nullable();
            $table->string('contact_urgence_nom')->nullable();
            $table->string('contact_urgence_tel')->nullable();
            $table->timestamps();
        });

        // Table des médecins
        Schema::create('medecins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('matricule')->unique()->index();
            $table->string('specialite');
            $table->string('titre')->default('Dr.');
            $table->string('bureau')->nullable();
            $table->unsignedInteger('duree_consultation_minutes')->default(30);
            $table->json('jours_consultation')->nullable();
            $table->timestamps();
        });

        // Table des secrétaires médicales
        Schema::create('secretaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('matricule')->unique()->index();
            $table->string('poste')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secretaires');
        Schema::dropIfExists('medecins');
        Schema::dropIfExists('patients');
    }
};
