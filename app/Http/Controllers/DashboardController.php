<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with real user statistics.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // 1. Calculate KPIs
        $totalAttempts = Attempt::where('user_id', $userId)->count();
        
        // Average Score (0 if no attempts)
        $avgScore = (int) round(Attempt::where('user_id', $userId)->avg('score') ?? 0);
        
        // Pass Rate
        $passedAttempts = Attempt::where('user_id', $userId)->where('passed', true)->count();
        $passRate = $totalAttempts > 0 
            ? (int) round(($passedAttempts / $totalAttempts) * 100) 
            : 0;

        // 2. Get Current Level
        // derived from the lesson of the latest attempt
        $currentLevel = Attempt::with('lesson.level')
            ->where('user_id', $userId)
            ->latest()
            ->first()
            ?->lesson
            ?->level;

        // 3. Last Unfinished Attempt
        // Checks for attempts that haven't been marked as finished_at
        $lastUnfinished = Attempt::with('lesson.level')
            ->where('user_id', $userId)
            ->whereNull('finished_at')
            ->latest()
            ->first();

        // 4. Recent Attempts (Table)
        $recentAttempts = Attempt::with(['lesson.level'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'lesson_id' => $attempt->lesson_id,
                    'lesson' => $attempt->lesson->title ?? $attempt->lesson->name ?? 'Unknown Lesson',
                    'score' => (int) ($attempt->score ?? 0),
                    'passed' => (bool) $attempt->passed,
                    'date' => $attempt->created_at ? $attempt->created_at->diffForHumans() : '-',
                ];
            })
            ->toArray();

        // 5. Category Performance 
        // Grouping by Lesson since Category doesnt exist
        // Metrics: accuracy based on attempt_answers.is_correct
        $categoryPerformance = DB::table('attempt_answers')
            ->join('attempts', 'attempts.id', '=', 'attempt_answers.attempt_id')
            ->join('lessons', 'lessons.id', '=', 'attempts.lesson_id')
            ->where('attempts.user_id', $userId)
            ->select(
                'lessons.title as name',
                DB::raw('COUNT(*) as total_answers'),
                DB::raw('SUM(CASE WHEN attempt_answers.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers')
            )
            ->groupBy('lessons.id', 'lessons.title') // Group by ID for stricter SQL modes, though title is key
            ->orderByDesc('total_answers')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $total = $row->total_answers;
                $correct = $row->correct_answers;
                $percent = $total > 0 ? (int) round(($correct / $total) * 100) : 0;
                
                return [
                    'name' => $row->name,
                    'percent' => $percent,
                    'meta' => "{$correct}/{$total} correct",
                ];
            })
            ->toArray();

        // 6. Return View
        return view('dashboard', compact(
            'totalAttempts',
            'avgScore',
            'passRate',
            'currentLevel',
            'lastUnfinished',
            'recentAttempts',
            'categoryPerformance'
        ));
    }
}
