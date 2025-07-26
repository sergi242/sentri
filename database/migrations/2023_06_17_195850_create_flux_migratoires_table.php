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
        Schema::create('flux_migratoires', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("frontieres_id");
            $table->integer("total_entree")->default(0);
            $table->integer("total_sortie")->default(0);
            $table->unsignedInteger("pays_id");
            $table->unsignedBigInteger("users_id");
            $table->date("date_movement");
            $table->timestamps();

            $table->foreign("frontieres_id")->references("id")->on("frontiere_congos")->cascadeOnDelete();
            $table->foreign("pays_id")->references("id")->on("pays")->cascadeOnDelete();
            $table->foreign("users_id")->references("id")->on("users")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flux_migratoires');
    }
};
