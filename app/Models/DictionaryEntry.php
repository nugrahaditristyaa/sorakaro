<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DictionaryEntry extends Model
{
    protected $fillable = [
        'karo_word',
        'indonesian_translation',
        'example_sentence',
        'example_translation',
    ];

    /**
     * Relevance-ordered search across karo_word and indonesian_translation.
     *
     * Priority order (most relevant first):
     *   1. Exact match   — "hor"  matches "hor"
     *   2. Starts with   — "hor"  matches "horas"
     *   3. Contains      — "hor"  matches "merdahor"
     *
     * This avoids heavy full-text search engines while still giving
     * users a natural "dictionary-app" search experience.
     *
     * Architecture note:
     * The scope is intentionally kept as a single query with a
     * UNION-free relevance_score column so pagination works correctly.
     *
     * Future extensibility:
     * - A UserDictionaryBookmark model can be added later to support
     *   bookmarks/favorites via a pivot without touching this model.
     * - Recent search history can be stored in a separate user_search_logs
     *   table if needed, again without modifying this class.
     *
     * @param  Builder  $query
     * @param  string   $term   Raw search string from user input
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        $safe  = mb_strtolower(trim($term));
        $exact = $safe;
        $start = $safe . '%';
        $any   = '%' . $safe . '%';

        return $query
            ->whereRaw(
                'LOWER(karo_word) LIKE ? OR LOWER(indonesian_translation) LIKE ?',
                [$any, $any]
            )
            ->orderByRaw(
                // Exact match on karo_word → highest priority (1)
                // Starts-with on karo_word → second priority (2)
                // Exact match on translation → third (3)
                // Starts-with on translation → fourth (4)
                // Anything else (partial contain) → lowest (5)
                "
                CASE
                    WHEN LOWER(karo_word) = ?               THEN 1
                    WHEN LOWER(karo_word) LIKE ?            THEN 2
                    WHEN LOWER(indonesian_translation) = ?  THEN 3
                    WHEN LOWER(indonesian_translation) LIKE ? THEN 4
                    ELSE 5
                END
                ",
                [$exact, $start, $exact, $start]
            )
            ->orderBy('karo_word'); // secondary alphabetical sort for ties
    }
}
