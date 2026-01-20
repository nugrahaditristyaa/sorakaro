<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'score',
        'total_questions',
        'finished_at',
        'passed',
    ];

    protected $casts = [
        'finished_at' => 'datetime',
        'passed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function answers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }
}
