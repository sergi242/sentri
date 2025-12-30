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
        Schema::create('quartiers', function (Blueprint $table) {
            $table->increments("id");
            $table->string("lib_quartier");
            $table->unsignedInteger("arrondissements_id");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("arrondissements_id")->references("id")->on("arrondissements")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quartiers');
    }
};
