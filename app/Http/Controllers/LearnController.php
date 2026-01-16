<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\AttemptAnswer;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Question;
use Illuminate\Http\Request;

class LearnController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $levels = \App\Models\Level::orderBy('order')->get();
        
        return view('learn.index', compact('levels', 'user'));
    }

    public function showLevel(\App\Models\Level $level)
    {
        // Load lessons with question count
        // Also fetch attempts for these lessons by current user
        $lessons = $level->lessons()
            ->withCount('questions')
            ->orderBy('order')
            ->get();

        $userId = auth()->id();
        
        // Get all attempts for these lessons
        $attempts = Attempt::where('user_id', $userId)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('lesson_id');

        // Attach attempt info to lessons manually
        foreach ($lessons as $lesson) {
            $lessonAttempts = $attempts->get($lesson->id, collect());
            
            $lesson->attempts_count = $lessonAttempts->count();
            $lesson->latest_attempt = $lessonAttempts->first();
            
            // Determine Status
            if ($lessonAttempts->isEmpty()) {
                $lesson->status = 'not_started'; // Belum mulai
            } elseif ($lesson->latest_attempt->finished_at === null) {
                $lesson->status = 'in_progress'; // Sedang
            } else {
                $lesson->status = 'completed';   // Selesai
            }
        }

        // Pass $lessons explicitly to override the lazy load in view if necessary, 
        // though view uses $level->lessons usually. We will update view to use $lessons provided here.
        return view('learn.level', compact('level', 'lessons'));
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        
        // Get user's current level (with fallback to first level)
        $currentLevel = null;
        if ($user->current_level_id) {
            $currentLevel = Level::find($user->current_level_id);
        }
        
        // Fallback to first level if user has no level set
        if (!$currentLevel) {
            $currentLevel = Level::orderBy('order')->first();
            
            // Auto-assign first level to user if they don't have one
            if ($currentLevel && !$user->current_level_id) {
                $user->update(['current_level_id' => $currentLevel->id]);
            }
        }
            
        // Find most recent unfinished attempt
        $lastUnfinished = Attempt::with(['lesson', 'lesson.level'])
            ->where('user_id', $user->id)
            ->whereNull('finished_at')
            ->latest('updated_at')
            ->first();

        return view('dashboard', compact('lastUnfinished', 'currentLevel'));
    }

    /**
     * Redirect to current level's guidebook
     */
    public function dashboardGuidebook(Request $request)
    {
        $user = $request->user();
        
        // Get user's current level
        $currentLevel = null;
        if ($user->current_level_id) {
            $currentLevel = Level::find($user->current_level_id);
        }
        
        // Fallback to first level
        if (!$currentLevel) {
            $currentLevel = Level::orderBy('order')->first();
        }
        
        // Redirect to guidebook page
        if ($currentLevel) {
            return redirect()->route('learn.guidebook', $currentLevel);
        }
        
        // If no levels exist, redirect back to dashboard with error
        return redirect()->route('dashboard')->with('error', 'No levels available yet.');
    }

    public function resume(Request $request, \App\Models\Attempt $attempt)
    {
        if ($attempt->user_id !== $request->user()->id || $attempt->finished_at) {
            return redirect()->route('learn.index');
        }
        
        session()->put('attempt_id', $attempt->id);
        
        // Find first unanswered question
        $answeredQIds = $attempt->answers()->pluck('question_id');
        $nextQuestion = $attempt->lesson->questions()
            ->whereNotIn('id', $answeredQIds)
            ->orderBy('order')
            ->orderBy('id')
            ->first();
            
        if (!$nextQuestion) {
             // All answered but not marked finished? Go to result
             return redirect()->route('learn.result', ['lesson' => $attempt->lesson_id, 'attempt' => $attempt->id]);
        }
        
        return redirect()->route('learn.question', [
            'lesson' => $attempt->lesson_id, 
            'question' => $nextQuestion->id
        ]);
    }

    public function start(Request $request, Lesson $lesson)
    {
        $user = $request->user();

        $totalQuestions = $lesson->questions()->count();

        if ($totalQuestions === 0) {
            return redirect()->back()
                ->with('error', 'Lesson ini belum punya soal.');
        }

        $attempt = Attempt::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'score' => 0,
            'total_questions' => $totalQuestions,
        ]);

        // Persist session properly
        session()->put('attempt_id', $attempt->id);

        // ambil soal pertama berdasarkan order lalu id
        $firstQuestion = $lesson->questions()
            ->orderBy('order')
            ->orderBy('id')
            ->first();

        return redirect()->route('learn.question', [
            'lesson' => $lesson->id,
            'question' => $firstQuestion->id,
        ]);
    }

    public function showQuestion(Request $request, Lesson $lesson, Question $question)
    {
        // 1. Basic Checks
        abort_unless($question->lesson_id === $lesson->id, 404);
        $attemptId = session('attempt_id');
        if (!$attemptId) {
            return redirect()->route('learn.level', $lesson->level_id)
                ->with('error', 'Silakan start lesson terlebih dahulu.');
        }

        $attempt = Attempt::where('id', $attemptId)
            ->where('user_id', $request->user()->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        // 2. Load all questions to determine navigation indexes
        $questions = $lesson->questions()->orderBy('order')->orderBy('id')->get();
        $currentIndex = $questions->search(function ($q) use ($question) {
            return $q->id === $question->id;
        });

        // 3. Navigation Links
        $prevQuestion = $questions[$currentIndex - 1] ?? null;
        $nextQuestion = $questions[$currentIndex + 1] ?? null;

        $prevUrl = $prevQuestion 
            ? route('learn.question', ['lesson' => $lesson->id, 'question' => $prevQuestion->id])
            : null;

        // Default Next URL is to the next question (skip logic if needed later, but standard flow goes to next)
        // However, we only show "Next" button if answered.
        $nextUrl = $nextQuestion 
            ? route('learn.question', ['lesson' => $lesson->id, 'question' => $nextQuestion->id])
            : route('learn.result', ['lesson' => $lesson->id, 'attempt' => $attempt->id]);


        // 4. Progress Info
        $progress = [
            'current' => $currentIndex + 1,
            'total' => $questions->count(),
        ];

        // 5. Choices & User Answer
        $choices = $question->choices()->orderBy('order')->orderBy('id')->get();
        
        $userAnswer = AttemptAnswer::where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->first();

        // Fetch all answers for this attempt to render progress state
        $userAnswers = AttemptAnswer::where('attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        return view('learn.question', compact(
            'lesson', 
            'question', 
            'questions', // Pass all questions for the progress bar
            'choices', 
            'attempt', 
            'userAnswer', // Current question's answer
            'userAnswers', // All answers
            'prevUrl',
            'nextUrl',
            'progress'
        ));
    }

    public function submitAnswer(Request $request, Lesson $lesson, Question $question)
    {
        abort_unless($question->lesson_id === $lesson->id, 404);

        $attemptId = session('attempt_id');
        if (!$attemptId) return redirect()->route('learn.index');

        $attempt = Attempt::findOrFail($attemptId);

        $validated = $request->validate([
            'choice_id' => ['required', 'integer', 'exists:choices,id'],
        ]);

        $choice = \App\Models\Choice::where('id', $validated['choice_id'])
            ->where('question_id', $question->id)
            ->firstOrFail();

        // Save Attempt
        AttemptAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
            ],
            [
                'choice_id' => $choice->id,
                'is_correct' => $choice->is_correct,
            ]
        );

        // Recalculate Score
        $score = AttemptAnswer::where('attempt_id', $attempt->id)
            ->where('is_correct', true)
            ->count();
        $attempt->update(['score' => $score]);

        // If last question, mark finished and check for level unlock
        $isLastQuestion = $lesson->questions()
            ->orderBy('order')->orderBy('id')
            ->get()->last()->id === $question->id;

        if ($isLastQuestion) {
            $attempt->update(['finished_at' => now()]);
            
            // Check and unlock next level if current level is completed
            $unlockService = app(\App\Services\LevelUnlockService::class);
            $unlockService->checkAndUnlockNextLevel($request->user(), $lesson);
        }

        // Redirect back for feedback
        return redirect()->route('learn.question', [
            'lesson' => $lesson->id,
            'question' => $question->id,
        ]);
    }

    public function result(Request $request, Lesson $lesson, Attempt $attempt)
    {
        abort_unless($attempt->lesson_id === $lesson->id, 404);
        abort_unless($attempt->user_id === $request->user()->id, 403);
        session()->forget('attempt_id');
        return view('learn.result', compact('lesson', 'attempt'));
    }
}
