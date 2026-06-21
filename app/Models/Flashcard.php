<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flashcard extends Model
{
    protected $fillable = [
        'flashcard_category_id',
        'karo_word',
        'indonesian_translation',
        'example_sentence',
        'example_translation',
        'order',
    ];

    /**
     * Get the category that owns this flashcard.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FlashcardCategory::class, 'flashcard_category_id');
    }
}
