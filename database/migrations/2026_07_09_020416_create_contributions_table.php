<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tontine_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('round_number');
            $table->foreignId('wallet_transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['paid', 'reversed'])->default('paid');
            $table->timestamps();

            $table->unique(['tontine_id', 'user_id', 'round_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};