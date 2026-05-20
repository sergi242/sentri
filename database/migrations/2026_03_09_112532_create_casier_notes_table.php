<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  // database/migrations/xxxx_create_casier_notes_table.php
public function up()
{
    Schema::create('casier_notes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('impetrant_id')->constrained('impetrants')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->text('note');
        $table->enum('niveau', ['info', 'warning', 'danger'])->default('info');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casier_notes');
    }
};
