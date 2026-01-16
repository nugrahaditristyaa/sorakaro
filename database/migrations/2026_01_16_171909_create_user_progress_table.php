<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('current_level_id')->nullable()->constrained('levels')->onDelete('set null');
            $table->foreignId('highest_unlocked_level_id')->nullable()->constrained('levels')->onDelete('set null');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('highest_unlocked_level_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
