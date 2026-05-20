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
        Schema::create('watchlists', function (Blueprint $table) {
    $table->id();

    // Identité
    $table->string('nom');
    $table->string('prenom')->nullable();
    $table->date('date_naissance')->nullable();
    $table->string('lieu_naissance')->nullable();
    $table->string('pays_naissance')->nullable();
    $table->string('nationalite')->nullable();
    $table->string('sexe')->nullable();

    // Filiation
    $table->string('nom_pere')->nullable();
    $table->string('prenom_pere')->nullable();
    $table->string('nom_mere')->nullable();
    $table->string('prenom_mere')->nullable();

    // Civil
    $table->string('etat_matrimonial')->nullable();
    $table->string('profession')->nullable();
    $table->string('adresse')->nullable();
    $table->string('telephone')->nullable();

    // Autres
    $table->string('photo')->nullable();
    $table->text('motif')->nullable();
    $table->integer('niveau_risque')->default(1);
    $table->boolean('actif')->default(true);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
