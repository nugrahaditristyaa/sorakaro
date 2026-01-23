<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    /**
     * Display the leaderboard rankings.
     */
    public function index(Request $request)
    {
        $range = $request->query('range', 'weekly');
        $limit = 20;

        // Determine date filter based on range
        // Default 'weekly' is last 7 days. 'all' is no filter.
        $dateFilter = $range === 'weekly' ? now()->subDays(7) : null;

        // Query Builder with Aggregation Joins
        // Structure: attempt_answers -> attempts -> users
        // We aggregate by user to get stats.
        
        $query = DB::table('attempt_answers')
            ->join('attempts', 'attempt_answers.attempt_id', '=', 'attempts.id')
            ->join('users', 'attempts.user_id', '=', 'users.id')
            ->select([
                'users.id',
                'users.name',
                // Primary Metric: Total Correct Answers
                DB::raw('SUM(attempt_answers.is_correct) as total_correct'),
                // Total Answers (for reference, requested by prompt)
                DB::raw('COUNT(attempt_answers.id) as total_answers'),
                // Total Attempts (Count distinct attempts)
                DB::raw('COUNT(DISTINCT attempts.id) as total_attempts'),
                // Passed Attempts (for pass rate calculation)
                DB::raw('COUNT(DISTINCT CASE WHEN attempts.passed = 1 THEN attempts.id END) as passed_attempts'),
                // Avg Score (Direct AVG requested, assuming uniform question counts or accepting weighted avg)
                DB::raw('AVG(attempts.score) as avg_score')
            ]);

        // Apply Date Filter
        if ($dateFilter) {
            $query->where('attempts.created_at', '>=', $dateFilter);
        }

        // Apply Group By and Ordering
        // Order: total_correct desc, pass_rate desc, avg_score desc, total_attempts desc
        $leaderboard = $query->groupBy('users.id', 'users.name')
            ->having('total_correct', '>', 0) // Ensure users with no correct answers/attempts don't clutter (or just > 0 attempts?) 
            // Prompt says: "Handle users with no attempts: they should not appear." 
            // The Inner Join on attempts/answers naturally excludes users with no attempts/answers.
            ->orderByDesc('total_correct')
            ->orderByRaw('(COUNT(DISTINCT CASE WHEN attempts.passed = 1 THEN attempts.id END) / COUNT(DISTINCT attempts.id)) DESC') // Pass Rate
            ->orderByDesc('avg_score')
            ->orderByDesc('total_attempts')
            ->limit($limit)
            ->get();

        // Process collection for display (calculate percentages)
        $leaderboard->transform(function ($item) {
            $item->pass_rate = $item->total_attempts > 0 
                ? round(($item->passed_attempts / $item->total_attempts) * 100, 1) 
                : 0;
            $item->avg_score = round($item->avg_score, 1);
            return $item;
        });

        return view('leaderboard.index', [
            'leaderboard' => $leaderboard,
            'range' => $range
        ]);
    }
}
