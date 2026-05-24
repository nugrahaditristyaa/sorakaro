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

        // ── Build query with filters ──────────────────────────────────────────
        $attempts = Attempt::with(['lesson.level'])
            ->where('user_id', $userId)
            // Filter: Pelajaran (lesson_id)
            ->when($request->filled('lesson_id'), function ($q) use ($request) {
                $q->where('lesson_id', $request->lesson_id);
            })
            // Filter: Tingkat (level_id) — via lesson → level relation
            ->when($request->filled('level_id'), function ($q) use ($request) {
                $q->whereHas('lesson', function ($lq) use ($request) {
                    $lq->where('level_id', $request->level_id);
                });
            })
            // Filter: Status (passed / failed)
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('passed', $request->status === 'passed');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // ── Build dropdown option lists ───────────────────────────────────────
        // Show only lessons/levels that this user has actually attempted,
        // so the filter dropdowns are relevant and not polluted with unused data.

        $attemptedLessonIds = Attempt::where('user_id', $userId)
            ->distinct()
            ->pluck('lesson_id');

        $lessonOptions = Lesson::whereIn('id', $attemptedLessonIds)
            ->orderBy('title')
            ->get(['id', 'title']);

        $levelOptions = Level::whereIn('id',
                Lesson::whereIn('id', $attemptedLessonIds)->distinct()->pluck('level_id')
            )
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('attempts.index', compact('attempts', 'lessonOptions', 'levelOptions'));
    }
}
