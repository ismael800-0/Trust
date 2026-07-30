<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // cycle_number already added from the previous partial run — skip re-adding it

        // Create the NEW unique index first, so the FK stays covered
        Schema::table('payouts', function (Blueprint $table) {
            $table->unique(['tontine_id', 'round_number', 'cycle_number'], 'payout_unique_cycle');
        });

        // Now safe to drop the old index
        DB::statement('ALTER TABLE payouts DROP INDEX payouts_tontine_id_round_number_unique');
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->unique(['tontine_id', 'round_number'], 'payouts_tontine_id_round_number_unique');
        });

        DB::statement('ALTER TABLE payouts DROP INDEX payout_unique_cycle');

        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('cycle_number');
        });
    }
};