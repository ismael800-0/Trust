<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tontines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('contribution_amount', 15, 2);
            $table->enum('frequency', ['daily', 'weekly', 'monthly']);
            $table->unsignedInteger('max_members');
            $table->date('start_date');
            $table->unsignedInteger('current_round')->default(1);
            $table->unsignedInteger('total_rounds_completed')->default(0);
            $table->timestamp('last_disbursement_at')->nullable();
            $table->enum('status', ['pending', 'active', 'completed', 'archived'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tontines');
    }
};