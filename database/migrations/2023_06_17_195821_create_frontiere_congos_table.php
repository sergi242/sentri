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
        Schema::create('frontiere_congos', function (Blueprint $table) {
            $table->increments("id");
            $table->string("lib_frontiere")->unique();
            $table->enum("terminal",["Port","Aeroport","Terrestre"]);
            $table->unsignedInteger("departements_id");
            $table->timestamps();

            $table->foreign("departements_id")->references("id")->on("departements")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('frontiere_congos');
    }
};
