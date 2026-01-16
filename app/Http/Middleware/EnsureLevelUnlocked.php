<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Level;

class EnsureLevelUnlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get the level from route parameter
        $level = $request->route('level');
        
        if (!$level instanceof Level) {
            // If level is ID, fetch it
            $level = Level::find($level);
        }

        if (!$level) {
            return redirect()->route('learn.index')
                ->with('error', 'Level not found.');
        }

        // Check if user has unlocked this level
        if (!$user->hasUnlockedLevel($level)) {
            return redirect()->route('learn.index')
                ->with('error', "This level is locked. Complete previous levels to unlock it.");
        }

        return $next($request);
    }
}
