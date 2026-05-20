<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('impetrants', function (Blueprint $table) {
            // Source d'enregistrement : 'demande' (flux normal) ou 'direct' (enregistrement sans demande)
            $table->enum('source', ['demande', 'direct'])->default('demande')->after('unique_string');
            // Qui a enregistré l'impétrant directement
            $table->unsignedBigInteger('created_by')->nullable()->after('source');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('impetrants', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['source', 'created_by']);
        });
    }
};
