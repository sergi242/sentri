<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // Ajout après created_by si la colonne existe, sinon en fin de table
            $table->unsignedBigInteger('commanditaire_id')->nullable()->after('created_by');

            $table->foreign('commanditaire_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropForeign(['commanditaire_id']);
            $table->dropColumn('commanditaire_id');
        });
    }
};
