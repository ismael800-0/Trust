<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tontine_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tontine_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'rejected', 'removed'])->default('pending');
            $table->unsignedInteger('position_in_cycle')->nullable();
            $table->timestamps();

            $table->unique(['tontine_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tontine_user');
    }
};