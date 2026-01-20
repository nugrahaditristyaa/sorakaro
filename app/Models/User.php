<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'age',
        'current_level_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Avatar icon based on gender
     */
    public function getAvatarIcon(): string
    {
        return match ($this->gender) {
            'male' => '🧑🏻‍💼',
            'female' => '👩🏻‍💼',
            default => '👤',
        };
    }

    /**
     * User's current level (users.current_level_id)
     */
    public function currentLevel()
    {
        return $this->belongsTo(Level::class, 'current_level_id');
    }

    /**
     * Progress record (user_progress)
     */
    public function progress()
    {
        return $this->hasOne(UserProgress::class);
    }

    /**
     * Attempts
     */
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    /**
     * Check if a level is unlocked for this user.
     * Source of truth: user_progress.highest_unlocked_level_id
     */

    public function hasUnlockedLevel(Level $level): bool
    {
        $highestId = \App\Models\UserProgress::where('user_id', $this->id)
            ->value('highest_unlocked_level_id');

        // If no progress yet: only the first level is unlocked
        if (!$highestId) {
            $firstLevelId = Level::orderBy('order')->value('id');
            return $firstLevelId ? ((int) $level->id === (int) $firstLevelId) : ($level->order === 1);
        }

        $highestOrder = Level::where('id', $highestId)->value('order') ?? 1;

        return (int) $level->order <= (int) $highestOrder;
    }

    /**
     * Highest unlocked order (helper)
     */
    public function getHighestUnlockedOrder(): int
    {
        $progress = $this->progress;

        if (!$progress || !$progress->highest_unlocked_level_id) {
            return 1;
        }

        $highestUnlocked = Level::find($progress->highest_unlocked_level_id);

        return $highestUnlocked?->order ?? 1;
    }
}
