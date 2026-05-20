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
       Schema::create('infraction_preuves', function (Blueprint $table) {
    $table->id();
    $table->foreignId('infraction_id')->constrained('infractions')->onDelete('cascade');
    $table->string('chemin_fichier');
    $table->string('nom_original')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infraction_preuves');
    }
};
