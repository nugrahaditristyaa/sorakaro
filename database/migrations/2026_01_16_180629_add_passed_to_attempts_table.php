<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->boolean('passed')->default(false)->after('finished_at');
        });

        // Backfill: Update passed=true for all attempts that have finished_at set.
        // Since we are introducing pass_rate now, we assume all previous completions were "passes"
        // to avoid locking users out of content they already completed.
        DB::table('attempts')
            ->whereNotNull('finished_at')
            ->update(['passed' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attempts', function (Blueprint $table) {
            $table->dropColumn('passed');
        });
    }
};
