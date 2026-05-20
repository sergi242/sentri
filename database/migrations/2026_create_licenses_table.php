<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            
            // Clé de licence
            $table->string('license_key')->unique();
            $table->string('license_key_display')->unique()->index();
            
            // Device binding (nullable au départ)
            $table->string('device_id')->nullable()->index();
            $table->string('device_name')->nullable();
            $table->string('device_ip')->nullable();
            
            // Durée
            $table->integer('duration_days')->default(30);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            // Statut
            $table->enum('status', ['active', 'expired', 'revoked', 'pending', 'used_once'])->default('pending');
            
            // Features JSON
            $table->json('features')->default(json_encode([
                'users' => true,
                'demandes' => true,
                'watchlist' => true,
                'reports' => true,
                'api' => true,
                'impetrants' => true,
                'flux_migratoires' => true,
            ]));
            
            // Limites
            $table->integer('max_users')->default(999);
            $table->integer('max_impetrants')->default(999999);
            $table->integer('max_demandes_per_day')->nullable();
            
            // Métadonnées
            $table->string('organization_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_validated_at')->nullable();
            $table->string('last_validated_ip')->nullable();
            $table->integer('validation_count')->default(0);
            
            // Audit
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['status', 'expires_at']);
            $table->index('device_id');
        });

        // Créer la table de logs de validation
        Schema::create('license_validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('licenses')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('action');
            $table->boolean('success')->default(true);
            $table->text('details')->nullable();
            $table->timestamp('created_at')->nullable();
            
            $table->index(['license_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('license_validations');
        Schema::dropIfExists('licenses');
    }
};
