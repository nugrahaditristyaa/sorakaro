<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'age',
        'current_level_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the avatar icon based on gender
     */
    public function getAvatarIcon(): string
    {
        return match($this->gender) {
            'male' => '🧑🏻‍💼',
            'female' => '👩🏻‍💼',
            default => '👤',
        };
    }

    /**
     * Get the user's current level
     */
    public function currentLevel()
    {
        return $this->belongsTo(Level::class, 'current_level_id');
    }

    /**
     * Get the user's progress record
     */
    public function progress()
    {
        return $this->hasOne(UserProgress::class);
    }

    /**
     * Get the user's attempts
     */
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    /**
     * Check if a level is unlocked for this user
     */
    public function hasUnlockedLevel(Level $level): bool
    {
        $progress = $this->progress;
        
        if (!$progress || !$progress->highestUnlockedLevel) {
            return $level->order === 1; // Only level 1 unlocked by default
        }
        
        return $level->order <= $progress->highestUnlockedLevel->order;
    }

    /**
     * Get highest unlocked level order
     */
    public function getHighestUnlockedOrder(): int
    {
        return $this->progress?->getHighestUnlockedOrder() ?? 1;
    }
}
