<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidebookItem extends Model
{
    protected $fillable = [
        'guidebook_section_id',
        'type',
        'text',
        'translation',
        'audio_path',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the section that owns this guidebook item
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(GuidebookSection::class, 'guidebook_section_id');
    }
}
