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
        Schema::create('fonctionnalites', function (Blueprint $table) {
            $table->increments("id");
            $table->string("lib_fonctionnalite")->unique();
            $table->unsignedInteger("fonctionnalite_parent")->nullable();
            $table->unsignedInteger("modules_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("modules_id")->references("id")->on("modules")->cascadeOnDelete();
            $table->foreign("fonctionnalite_parent")->references("id")->on("fonctionnalites")->cascadeOnDelete();
        });

        Schema::create('roles_fonctionnalites', function (Blueprint $table) {
            $table->primary(["roles_id","fonctionnalites_id"]);
            $table->unsignedInteger("roles_id");
            $table->unsignedInteger("fonctionnalites_id");
            $table->timestamps();

            $table->foreign("roles_id")->references("id")->on("roles")->cascadeOnDelete();
            $table->foreign("fonctionnalites_id")->references("id")->on("fonctionnalites")->cascadeOnDelete();
        });

        Schema::create('users_fonctionnalites', function (Blueprint $table) {
            $table->primary(["users_id","fonctionnalites_id"]);
            $table->unsignedBigInteger("users_id");
            $table->unsignedInteger("fonctionnalites_id");
            $table->timestamps();

            $table->foreign("users_id")->references("id")->on("users")->cascadeOnDelete();
            $table->foreign("fonctionnalites_id")->references("id")->on("fonctionnalites")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_fonctionnalites');
        Schema::dropIfExists('roles_fonctionnalites');
        Schema::dropIfExists('fonctionnalites');
    }
};
