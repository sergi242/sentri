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
        Schema::table('demandes', function (Blueprint $table) {
            $table->unsignedInteger("categorie_socioprof_id")->nullable();
            $table->foreign("categorie_socioprof_id")->references("id")->on("categorie_socio_professionnelles")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns("demandes","categorie_socioprof_id");
    }
};
