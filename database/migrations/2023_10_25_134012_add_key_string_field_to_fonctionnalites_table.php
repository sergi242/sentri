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
        Schema::table('fonctionnalites', function (Blueprint $table) {
            $table->string("unique_key_string")->nullable()->after("lib_fonctionnalite")->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns("fonctionnalites",["unique_key_string"]);
    }
};
