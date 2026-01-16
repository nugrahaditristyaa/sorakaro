<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['name', 'description', 'order'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function guidebookSections()
    {
        return $this->hasMany(GuidebookSection::class)->orderBy('order');
    }
}
