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
        Schema::create('arrondissements', function (Blueprint $table) {
            $table->increments("id");
            $table->string("lib_arrondissement");
            $table->unsignedInteger("departements_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("departements_id")->references("id")->on("departements")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrondissements');
    }
};
