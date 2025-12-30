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
        Schema::create('impetrants', function (Blueprint $table) {
            $table->id();
            $table->string("nom");
            $table->string("prenom")->nullable();
            $table->enum("sexe",["Masculin","Féminin"]);
            $table->date("date_naissance");
            $table->string("lieu_naissance");
            $table->unsignedInteger("nationalites_id");
            $table->string("nom_pere");
            $table->string("prenom_pere");
            $table->string("nom_mere");
            $table->string("prenom_mere");
            $table->text("unique_string");
            $table->unsignedBigInteger("users_id")->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("users_id")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impetrants');
    }
};
