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
    Schema::table('demandes', function (Blueprint $table) {
        if (!Schema::hasColumn('demandes', 'retire_par')) {
            $table->unsignedBigInteger('retire_par')->nullable();
        }

        if (!Schema::hasColumn('demandes', 'retire_le')) {
            $table->timestamp('retire_le')->nullable();
        }
    });
}


    /**
     * Reverse the migrations.
     */
   public function down(): void
{
    Schema::table('demandes', function (Blueprint $table) {
        $table->dropColumn(['retire_par', 'retire_le']);
    });
}

};
