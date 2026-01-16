<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    protected $table = 'user_progress';
    
    protected $fillable = [
        'user_id',
        'current_level_id',
        'highest_unlocked_level_id',
    ];

    /**
     * Get the user that owns this progress
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the current level
     */
    public function currentLevel(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'current_level_id');
    }

    /**
     * Get the highest unlocked level
     */
    public function highestUnlockedLevel(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'highest_unlocked_level_id');
    }

    /**
     * Check if a level is unlocked for this user
     */
    public function isLevelUnlocked(Level $level): bool
    {
        if (!$this->highestUnlockedLevel) {
            return false;
        }
        
        return $level->order <= $this->highestUnlockedLevel->order;
    }

    /**
     * Get the highest unlocked level order
     */
    public function getHighestUnlockedOrder(): int
    {
        return $this->highestUnlockedLevel?->order ?? 0;
    }
}
