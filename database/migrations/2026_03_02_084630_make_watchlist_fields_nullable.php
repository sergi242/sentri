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
    Schema::table('watchlists', function (Blueprint $table) {
        $table->string('nom')->nullable()->change();
    });
}

public function down()
{
    Schema::table('watchlists', function (Blueprint $table) {
        $table->string('nom')->nullable(false)->change();
    });
}
};
