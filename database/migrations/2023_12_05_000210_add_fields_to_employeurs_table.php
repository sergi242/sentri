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
        Schema::table("employeurs", function (Blueprint $table) {
            $table->string("telephone",15)->nullable()->after("adresse_physique");
            $table->string("email")->nullable()->after("telephone");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropColumns("employeurs",["telephone","email"]);
    }
};
