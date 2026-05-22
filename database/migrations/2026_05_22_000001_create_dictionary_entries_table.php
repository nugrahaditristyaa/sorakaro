<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * dictionary_entries
     *
     * Stores Karo ↔ Indonesian vocabulary pairs for the Kamus feature.
     *
     * Design decisions:
     * - karo_word is UNIQUE — no duplicate entries allowed.
     * - Indexes on both karo_word and indonesian_translation for fast LIKE searches.
     * - example_sentence + example_translation are nullable — many words won't have examples.
     * - No audio, images, categories, or difficulty — kept intentionally simple.
     *
     * Future extensibility (not implemented):
     * - A pivot table user_dictionary_bookmarks(user_id, dictionary_entry_id) can be added
     *   later for bookmarks/favorites without touching this table.
     */
    public function up(): void
    {
        Schema::create('dictionary_entries', function (Blueprint $table) {
            $table->id();

            // The Karo word — must be unique to prevent duplicate entries
            $table->string('karo_word')->unique();

            // Indonesian translation (can be multi-word / comma-separated meanings)
            $table->text('indonesian_translation');

            // Optional example usage in Karo language
            $table->text('example_sentence')->nullable();

            // Optional Indonesian translation of the example sentence
            $table->text('example_translation')->nullable();

            $table->timestamps();

            // Indexes for performant LIKE search on both searchable columns
            $table->index('karo_word');
            $table->index('indonesian_translation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dictionary_entries');
    }
};
