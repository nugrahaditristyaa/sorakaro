<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class LearningSession extends Model
{
    // ─── Status Constants ─────────────────────────────────────────────────────
    // Use these instead of raw strings to prevent silent typo bugs.

    public const STATUS_NOT_STARTED    = 'not_started';
    public const STATUS_PRETEST_DONE   = 'pretest_done';
    public const STATUS_GUIDEBOOK_DONE = 'guidebook_done';
    public const STATUS_POSTTEST_DONE  = 'posttest_done';
    public const STATUS_COMPLETED      = 'completed';

    /** All statuses that represent an in-progress (non-completed) session. */
    public const ACTIVE_STATUSES = [
        self::STATUS_NOT_STARTED,
        self::STATUS_PRETEST_DONE,
        self::STATUS_GUIDEBOOK_DONE,
        self::STATUS_POSTTEST_DONE,
    ];

    protected $fillable = [
        'user_id',
        'pretest_attempt_id',
        'posttest_attempt_id',
        'status',
        'level_id',
        'improvement',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function pretestAttempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class, 'pretest_attempt_id');
    }

    public function posttestAttempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class, 'posttest_attempt_id');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Map a 0–100 percentage score to a Level record ID if possible, 
     * or return the lowest level. (Can be customized)
     */
    public static function determineLevelId(int $percentage): ?int
    {
        $label = match(true) {
            $percentage >= 85 => 'B2',
            $percentage >= 70 => 'B1',
            $percentage >= 50 => 'A2',
            default           => 'A1',
        };

        $level = Level::where('name', 'like', "%{$label}%")->first()
            ?? Level::orderBy('order')->first();
            
        return $level?->id;
    }
    
    /**
     * Helper to get a nicely formatted level name based on the level relation.
     */
    public function getLevelName(): string
    {
        return $this->level ? $this->level->name : 'A1';
    }

    /**
     * Whether this session has completed the pretest step.
     * NOTE: 'not_started' is explicitly excluded — the user must finish the pretest first.
     */
    public function hasDonePretest(): bool
    {
        return in_array($this->status, [
            self::STATUS_PRETEST_DONE,
            self::STATUS_GUIDEBOOK_DONE,
            self::STATUS_POSTTEST_DONE,
            self::STATUS_COMPLETED,
        ]);
    }

    /**
     * Whether this session has completed the guidebook step.
     */
    public function hasDoneGuidebook(): bool
    {
        return in_array($this->status, [
            self::STATUS_GUIDEBOOK_DONE,
            self::STATUS_POSTTEST_DONE,
            self::STATUS_COMPLETED,
        ]);
    }

    /**
     * Whether this session has completed the posttest step.
     */
    public function hasDonePosttest(): bool
    {
        return in_array($this->status, [
            self::STATUS_POSTTEST_DONE,
            self::STATUS_COMPLETED,
        ]);
    }

    /**
     * Whether this session is still active (not completed).
     */
    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE_STATUSES);
    }

    /**
     * Resolves the correct route based on the current session status.
     *
     * This is the SINGLE SOURCE OF TRUTH for learning navigation.
     * Every status maps to exactly one route — no ambiguous defaults.
     */
    public function resolveLearningRoute(): string
    {
        $route = match($this->status) {
            self::STATUS_NOT_STARTED    => 'learning.pretest',
            self::STATUS_PRETEST_DONE   => 'learning.guidebook',
            self::STATUS_GUIDEBOOK_DONE => 'learning.posttest',
            self::STATUS_POSTTEST_DONE  => 'learning.result',
            self::STATUS_COMPLETED      => 'learning.result',
            default => null,
        };

        if ($route === null) {
            Log::error("[LearningSession#{$this->id}] Unknown status '{$this->status}' — cannot resolve route.");
            return 'learning.pretest'; // safe fallback, should never happen
        }

        Log::debug("[LearningSession#{$this->id}] status='{$this->status}' → route='{$route}'");

        return $route;
    }
}
