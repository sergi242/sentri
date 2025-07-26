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
        Schema::create('contentieuxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("demandes_id");
            $table->unsignedInteger("motifs_id");
            $table->string("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contentieuxes');
    }
};
