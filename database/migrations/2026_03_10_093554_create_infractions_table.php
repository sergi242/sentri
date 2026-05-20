<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('infractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impetrant_id')->constrained('impetrants')->onDelete('cascade');
            $table->foreignId('demande_id')->nullable()->constrained('demandes')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', [
                'expiration_sans_renouvellement',
                'demande_expiree_sans_suite',
                'contentieux',
                'manuelle'
            ]);
            $table->enum('gravite', ['mineur', 'moyen', 'grave'])->default('mineur');
            $table->enum('statut', ['en_cours', 'resolu', 'classe'])->default('en_cours');
            $table->text('motif');
            $table->date('date_infraction');
            $table->boolean('auto_generee')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infractions');
    }
};