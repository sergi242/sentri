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
        Schema::create('similarity_rejections', function (Blueprint $table) {
    $table->id();

    // Demande de référence (celle qu’on consulte)
    $table->foreignId('demande_base_id')
        ->constrained('demandes')
        ->cascadeOnDelete();

    // Demande rejetée comme similaire
    $table->foreignId('demande_similaire_id')
        ->constrained('demandes')
        ->cascadeOnDelete();

    // Agent ayant pris la décision
    $table->foreignId('user_id')
        ->constrained('users');

    $table->timestamps();

    $table->unique(
        ['demande_base_id', 'demande_similaire_id'],
        'unique_directional_rejection'
    );
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('similarity_rejections');
    }
};
