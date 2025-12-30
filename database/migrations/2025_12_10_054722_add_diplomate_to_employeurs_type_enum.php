<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE employeurs MODIFY type ENUM('Personne morale', 'Personne physique', 'Diplomate') DEFAULT 'Personne morale'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE employeurs MODIFY type ENUM('Personne morale', 'Personne physique') DEFAULT 'Personne morale'");
    }
};