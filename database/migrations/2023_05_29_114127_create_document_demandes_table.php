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
        Schema::create('document_demandes', function (Blueprint $table) {
            $table->id();
            $table->enum("type_document",["Passeport","Carte consulaire"]);
            $table->string("numero_document");
            $table->date("date_emission");
            $table->date("date_expiration");
            $table->string("emis_par")->nullable();
            $table->unsignedBigInteger("demandes_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("demandes_id")->references("id")->on("demandes")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_demandes');
    }
};
