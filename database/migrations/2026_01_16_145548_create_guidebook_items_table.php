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
        Schema::create('guidebook_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guidebook_section_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['phrase', 'tip'])->default('phrase');
            $table->text('text'); // Main sentence/content
            $table->text('translation')->nullable();
            $table->string('audio_path')->nullable(); // Future-proof for audio
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['guidebook_section_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guidebook_items');
    }
};
