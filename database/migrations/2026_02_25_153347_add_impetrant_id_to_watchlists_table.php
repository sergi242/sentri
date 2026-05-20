<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('watchlists', function (Blueprint $table) {

            $table->unsignedBigInteger('impetrant_id')
                  ->nullable()
                  ->after('user_id');

            $table->foreign('impetrant_id')
                  ->references('id')
                  ->on('impetrants')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->dropForeign(['impetrant_id']);
            $table->dropColumn('impetrant_id');
        });
    }
};
