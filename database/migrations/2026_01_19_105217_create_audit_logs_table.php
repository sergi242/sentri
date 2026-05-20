<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();

            // Utilisateur
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();

            // Action
            $table->string('action');
            $table->string('module')->nullable();

            // Entité concernée
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();

            // Données
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Résultat
            $table->enum('status', ['success', 'failed', 'forbidden']);

            // Contexte
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('route')->nullable();
            $table->string('method')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index('entity_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
