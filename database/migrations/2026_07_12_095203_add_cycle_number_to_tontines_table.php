<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tontines', function (Blueprint $table) {
            $table->unsignedInteger('cycle_number')->default(1)->after('total_rounds_completed');
        });
    }

    public function down(): void
    {
        Schema::table('tontines', function (Blueprint $table) {
            $table->dropColumn('cycle_number');
        });
    }
};