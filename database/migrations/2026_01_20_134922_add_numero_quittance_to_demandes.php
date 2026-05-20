<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajout du numéro de quittance
     */
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->string('numero_quittance')
                  ->nullable()
                  ->after('id');
        });
    }

    /**
     * Suppression du numéro de quittance
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn('numero_quittance');
        });
    }
};
