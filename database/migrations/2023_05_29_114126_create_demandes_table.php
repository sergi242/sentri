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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("impetrants_id");
            $table->string("numero_ancien_document")->nullable();
            $table->string("photo");
            $table->string("numero_document")->nullable();
            $table->enum("validite",["1","3","5"])->default(1);
            $table->enum("etat_civil",["Célibataire","Marié(e)","Divorcé(e)","Veuf(-ve)"]);
            $table->date("date_emission")->nullable();
            $table->date("date_expiration")->nullable();
            $table->unsignedInteger("quartiers_id")->nullable();
            $table->string("avenue_rue");
            $table->string("numero_adresse");
            $table->string("telephone")->nullable();
            $table->string("email")->nullable();
            $table->string("profession")->nullable();
            $table->string("employeur")->nullable();
            $table->string("adresse_employeur")->nullable();
            $table->enum("type_demande",["Carte de résident temporaire","Visa"]);
            $table->date("date_demande");
            $table->enum("statut_demande",["En attente d\'approbation","Approuvée","Rejetée","Envoyée au contentieux","Renvoyée à la saisie pour modification","Livrée"])->default("En attente d\'approbation");
            $table->unsignedBigInteger("created_by")->nullable();
            $table->unsignedBigInteger("approved_by")->nullable();
            $table->timestamp("approval_date")->nullable();
            $table->uuid();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("impetrants_id")->references("id")->on("impetrants")->cascadeOnDelete();
            $table->foreign("approved_by")->references("id")->on("users")->cascadeOnDelete();
            $table->foreign("created_by")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }

};
