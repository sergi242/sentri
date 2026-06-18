<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('demandes', function (Blueprint $table) {
            $table->date('date_quittance')->nullable()->after('numero_quittance');
        });
    }
    public function down(): void {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn('date_quittance');
        });
    }
};
