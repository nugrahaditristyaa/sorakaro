<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuidebookSection extends Model
{
    protected $fillable = [
        'level_id',
        'title',
        'subtitle',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the level that owns this guidebook section
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the items for this guidebook section
     */
    public function items(): HasMany
    {
        return $this->hasMany(GuidebookItem::class)->orderBy('order');
    }
}
