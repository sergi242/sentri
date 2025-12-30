<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->unsignedBigInteger('attribue_par')->nullable()->after('attribue');

            $table->foreign('attribue_par')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropForeign(['attribue_par']);
            $table->dropColumn('attribue_par');
        });
    }
};


