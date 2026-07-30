<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // cycle_number already exists from the previous partial run — no need to add it again

        // Create the NEW unique index first, so the FK is never left uncovered
        Schema::table('contributions', function (Blueprint $table) {
            $table->unique(['tontine_id', 'user_id', 'round_number', 'cycle_number'], 'contrib_unique_cycle');
        });

        // Now safe to drop the old index
        DB::statement('ALTER TABLE contributions DROP INDEX contributions_tontine_id_user_id_round_number_unique');
    }

    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->unique(['tontine_id', 'user_id', 'round_number'], 'contributions_tontine_id_user_id_round_number_unique');
        });

        DB::statement('ALTER TABLE contributions DROP INDEX contrib_unique_cycle');

        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('cycle_number');
        });
    }
};