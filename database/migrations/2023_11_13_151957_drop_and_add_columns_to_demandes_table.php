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
        Schema::dropColumns("demandes",["employeur","adresse_employeur"]);

        Schema::table('demandes', function (Blueprint $table) {
            $table->unsignedInteger("employeur_id")->nullable()->after("profession");
            $table->foreign("employeur_id")->references("id")->on("employeurs")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
