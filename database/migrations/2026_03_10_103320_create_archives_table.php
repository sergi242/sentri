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
        Schema::create('archives', function (Blueprint $table) {
    $table->id();
    $table->foreignId('impetrant_id')->constrained('impetrants')->onDelete('cascade');
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
    $table->enum('type_document', [
        'passeport',
        'carte_consulaire',
        'visa',
        'carte_resident',
        'attestation_employeur',
        'contrat_bail',
        'visa_entree',
        'piece_identite',
        'autre'
    ]);
    $table->string('libelle')->nullable(); // précision si "autre"
    $table->string('numero_document')->nullable();
    $table->date('date_emission')->nullable();
    $table->date('date_expiration')->nullable();
    $table->string('chemin_fichier');
    $table->string('nom_original')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
