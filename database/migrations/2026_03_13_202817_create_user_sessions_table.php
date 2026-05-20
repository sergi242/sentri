<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('login_at')->nullable();
            $table->timestamp('logout_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();

            $table->index('user_id');
            $table->index('login_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
