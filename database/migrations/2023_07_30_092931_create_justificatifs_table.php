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
        Schema::create('justificatifs', function (Blueprint $table) {
            $table->increments("id");
            $table->string("piece",100)->unique();
            $table->timestamps();
        });

        Schema::create("demandes_pieces",function(Blueprint $table){
            $table->primary(["demandes_id","pieces_id"]);
            $table->unsignedBigInteger("demandes_id");
            $table->unsignedInteger("pieces_id");
            $table->timestamps();

            $table->foreign("demandes_id")->references("id")->on("demandes")->cascadeOnDelete();
            $table->foreign("pieces_id")->references("id")->on("justificatifs")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes_pieces');
        Schema::dropIfExists('justificatifs');
    }
};
