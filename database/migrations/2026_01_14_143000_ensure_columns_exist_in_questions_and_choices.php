<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns to choices table if they don't exist
        Schema::table('choices', function (Blueprint $table) {
            if (!Schema::hasColumn('choices', 'question_id')) {
                $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            }

            if (!Schema::hasColumn('choices', 'text')) {
                $table->string('text');
            }
            
            if (!Schema::hasColumn('choices', 'is_correct')) {
                $table->boolean('is_correct')->default(false);
            }
            
            if (!Schema::hasColumn('choices', 'order')) {
                $table->unsignedInteger('order')->default(0);
            }
        });
    }

    public function down(): void
    {
        // Non-destructive down
    }
};
