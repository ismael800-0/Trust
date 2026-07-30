<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tontine_id')->constrained()->onDelete('cascade');
            $table->foreignId('beneficiary_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('round_number');
            $table->foreignId('wallet_transaction_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();

            $table->unique(['tontine_id', 'round_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};