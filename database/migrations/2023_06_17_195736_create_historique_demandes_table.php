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
        Schema::create('historique_demandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("demandes_id");
            $table->enum("statut_demande",["En attente d\'approbation","Approuvée","Rejetée","Envoyée au contentieux","Renvoyée à la saisie pour modification","Livrée"])->default("En attente d\'approbation");
            $table->unsignedBigInteger("users_id");
            $table->timestamps();

            $table->foreign("demandes_id")->references("id")->on("demandes")->cascadeOnDelete();
            $table->foreign("users_id")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historique_demandes');
    }
};
