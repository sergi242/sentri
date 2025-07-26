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
        Schema::table('soit_transmis', function (Blueprint $table) {
            $table->unsignedBigInteger('commanditaire_id')->nullable()->after('users_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->after('commanditaire_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by')->nullable();
            $table->timestamp('date_modification')->nullable()->after('updated_by')->nullable();

            $table->foreign('commanditaire_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soit_transmis', function (Blueprint $table) {
            //
        });
    }
};
