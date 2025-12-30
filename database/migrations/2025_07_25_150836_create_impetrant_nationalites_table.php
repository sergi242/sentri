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
        Schema::create('impetrant_nationalites', function (Blueprint $table) {
            $table->id();
            $table->foreignId("impetrant_id")->constrained("impetrants")->onDelete("cascade");
            $table->unsignedInteger("pays_id");
            $table->timestamps();
            $table->foreign("pays_id")->references("id")->on("pays")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impetrant_nationalites');
    }
};
