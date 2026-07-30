<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tontine_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tontine_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->boolean('resolved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tontine_flags');
    }
};