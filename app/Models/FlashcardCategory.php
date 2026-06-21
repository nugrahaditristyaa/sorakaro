<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlashcardCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'order',
    ];

    /**
     * Get the flashcards for this category.
     */
    public function flashcards(): HasMany
    {
        return $this->hasMany(Flashcard::class)->orderBy('order');
    }
}
