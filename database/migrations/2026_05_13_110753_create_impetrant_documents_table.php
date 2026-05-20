<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impetrant_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('impetrants_id');
            $table->enum('type_document', ['Passeport', 'Titre de voyage', 'Laissez-passer', 'Autre'])->default('Passeport');
            $table->string('numero_document', 50);
            $table->date('date_delivrance')->nullable();
            $table->date('date_expiration')->nullable();
            $table->unsignedBigInteger('pays_delivrance_id')->nullable();
            $table->text('mrz')->nullable();
            $table->enum('source', ['lecteur', 'manuel'])->default('manuel');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('impetrants_id')
                  ->references('id')->on('impetrants')
                  ->onDelete('cascade');

            $table->foreign('pays_delivrance_id')
                  ->references('id')->on('pays')
                  ->onDelete('set null');

            $table->foreign('created_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            // Index pour recherche rapide par numéro de document
            $table->index('numero_document');
            $table->index('impetrants_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impetrant_documents');
    }
};
