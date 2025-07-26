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
        Schema::create('employeurs', function (Blueprint $table) {
            $table->increments("id");
            $table->string("nom_employeur")->unique();
            $table->enum("type",["Personne morale","Personne physique"])->default("Personne morale");
            $table->string("adresse_physique");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employeurs');
    }
};
