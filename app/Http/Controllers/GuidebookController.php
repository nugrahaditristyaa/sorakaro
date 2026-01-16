<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\GuidebookSection;
use Illuminate\Http\Request;

class GuidebookController extends Controller
{
    /**
     * Display the guidebook for a specific level
     */
    public function show(Level $level)
    {
        // Eager load sections with their items (active only)
        $sections = GuidebookSection::where('level_id', $level->id)
            ->where('is_active', true)
            ->with(['items' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return view('learn.guidebook', compact('level', 'sections'));
    }
}
