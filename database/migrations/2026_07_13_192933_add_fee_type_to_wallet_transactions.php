<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'contribution', 'payout', 'fee') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE wallet_transactions MODIFY COLUMN type ENUM('deposit', 'withdrawal', 'contribution', 'payout') NOT NULL");
    }
};