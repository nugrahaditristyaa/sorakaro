<?php

namespace App\Http\Controllers;


use App\Models\Attempt;
use App\Models\Lesson;
use App\Models\Level;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Build base query
        $query = Attempt::with(['lesson.level'])
            ->where('user_id', $userId);

        // Apply Filters
        if ($request->filled('lesson_id')) {
            $query->where('lesson_id', $request->lesson_id);
        }

        if ($request->filled('level_id')) {
            $query->whereHas('lesson.level', function ($q) use ($request) {
                $q->where('id', $request->level_id);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'passed') {
                $query->where('passed', true);
            } elseif ($request->status === 'failed') {
                $query->where('passed', false);
            }
        }

        // Get Paginated Results
        $attempts = $query->latest()
            ->paginate(10)
            ->withQueryString();

        // ----------------------------------------------------
        // Build Filter Dropdown Data (Only distinct/relevant items)
        // ----------------------------------------------------
        
        // Optimize: Get distinct lesson IDs from this user's attempts first
        $attemptedLessonIds = Attempt::where('user_id', $userId)
            ->distinct()
            ->pluck('lesson_id');

        $lessonOptions = Lesson::whereIn('id', $attemptedLessonIds)
            ->orderBy('title')
            ->get(['id', 'title']);

        // Get levels from those lessons
        $levelOptions = Level::whereIn('id', function($q) use ($attemptedLessonIds) {
                $q->select('level_id')
                  ->from('lessons')
                  ->whereIn('id', $attemptedLessonIds);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('attempts.index', compact('attempts', 'lessonOptions', 'levelOptions'));
    }
}
