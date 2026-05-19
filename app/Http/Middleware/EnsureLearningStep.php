<?php

namespace App\Http\Middleware;

use App\Models\LearningSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prevents users from skipping steps in the guided learning flow.
 *
 * Guards:
 *   /learning/pretest   → allows any active session with level_id
 *   /learning/guidebook → requires pretest_done
 *   /learning/posttest  → requires guidebook_done
 *   /learning/result    → requires a completed session
 *
 * IMPORTANT: Only sessions with level_id set are considered valid.
 * Ghost sessions (no level_id) are ignored — they are cleaned up by
 * LearningController::getActiveSession().
 */
class EnsureLearningStep
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $routeName = $request->route()?->getName();

        /** @var LearningSession|null $session */
        $session = LearningSession::where('user_id', $user->id)
            ->whereIn('status', LearningSession::ACTIVE_STATUSES)
            ->whereNotNull('level_id')
            ->latest()
            ->first();

        Log::debug("[EnsureLearningStep] route='{$routeName}' user={$user->id} session=" . ($session ? "#{$session->id} status={$session->status}" : 'null'));

        // ── Result: needs a completed session ─────────────────────────────────
        // The result page is special: it shows completed sessions, NOT active ones.
        if ($routeName === 'learning.result') {
            $completedSession = LearningSession::where('user_id', $user->id)
                ->where('status', LearningSession::STATUS_COMPLETED)
                ->whereNotNull('posttest_attempt_id')
                ->latest()
                ->first();

            if (! $completedSession) {
                $fallback = $session ? $session->resolveLearningRoute() : 'learning.start';
                Log::warning("[EnsureLearningStep] result guard failed for user {$user->id}. No completed session. Redirecting to '{$fallback}'.");
                return redirect()->route($fallback)
                    ->with('error', 'Selesaikan post-test terlebih dahulu.');
            }

            return $next($request);
        }

        // ── Guidebook: need pretest_done ──────────────────────────────────────
        if ($routeName === 'learning.guidebook') {
            if (! $session || ! $session->hasDonePretest() || ! $session->pretest_attempt_id) {
                $fallback = $session ? $session->resolveLearningRoute() : 'learning.start';
                Log::warning("[EnsureLearningStep] guidebook guard failed for user {$user->id}: session=" . ($session ? "#{$session->id} status={$session->status}" : 'null') . ". Redirecting to '{$fallback}'.");
                return redirect()->route($fallback)
                    ->with('error', 'Selesaikan pretest terlebih dahulu.');
            }
        }

        // ── Posttest: need guidebook_done ─────────────────────────────────────
        if ($routeName === 'learning.posttest') {
            if (! $session || ! $session->hasDoneGuidebook() || ! $session->pretest_attempt_id) {
                $fallback = $session ? $session->resolveLearningRoute() : 'learning.start';
                Log::warning("[EnsureLearningStep] posttest guard failed for user {$user->id}: session=" . ($session ? "#{$session->id} status={$session->status}" : 'null') . ". Redirecting to '{$fallback}'.");
                return redirect()->route($fallback)
                    ->with('error', 'Baca panduan terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
