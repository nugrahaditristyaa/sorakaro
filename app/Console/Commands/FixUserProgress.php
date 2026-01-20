<?php

namespace App\Console\Commands;

use App\Models\Level;
use App\Models\User;
use App\Models\UserProgress;
use App\Services\LevelUnlockService;
use Illuminate\Console\Command;

class FixUserProgress extends Command
{
    protected $signature = 'sorakaro:fix-user-progress
                            {user_id? : Target user id}
                            {--all : Fix all users}
                            {--reset : Force reset to first level (ignore attempts)}
                            {--dry : Dry run (no DB writes)}';

    protected $description = 'Fix / backfill Sorakaro user progress (current_level_id & highest_unlocked_level_id) based on levels order and attempts history';

    public function handle(LevelUnlockService $service): int
    {
        $firstLevel = Level::orderBy('order')->first();

        if (!$firstLevel) {
            $this->error('No levels found in database.');
            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry');
        $reset = (bool) $this->option('reset');
        $all = (bool) $this->option('all');

        if ($all) {
            $users = User::query()->select('id', 'current_level_id')->get();
        } else {
            $userId = $this->argument('user_id');

            if (!$userId) {
                $this->error('Provide {user_id} or use --all.');
                return self::FAILURE;
            }

            $user = User::query()->find($userId);
            if (!$user) {
                $this->error("User not found: {$userId}");
                return self::FAILURE;
            }

            $users = collect([$user]);
        }

        $this->info('Sorakaro progress fixer started.');
        $this->line("Mode: " . ($dry ? 'DRY RUN' : 'WRITE') . " | " . ($reset ? 'RESET' : 'BACKFILL'));

        foreach ($users as $user) {
            $this->line("— User #{$user->id}");

            $existing = UserProgress::where('user_id', $user->id)->first();

            if ($reset) {
                // Force reset to first level
                if ($dry) {
                    $this->comment("  [DRY] Would set user_progress to first level id={$firstLevel->id} (order={$firstLevel->order})");
                    $this->comment("  [DRY] Would set users.current_level_id={$firstLevel->id}");
                    continue;
                }

                if (!$existing) {
                    $existing = UserProgress::create([
                        'user_id' => $user->id,
                        'current_level_id' => $firstLevel->id,
                        'highest_unlocked_level_id' => $firstLevel->id,
                    ]);
                } else {
                    $existing->update([
                        'current_level_id' => $firstLevel->id,
                        'highest_unlocked_level_id' => $firstLevel->id,
                    ]);
                }

                $user->update(['current_level_id' => $firstLevel->id]);

                $this->info("  Reset OK → current={$firstLevel->id}, highest={$firstLevel->id}");
                continue;
            }

            // BACKFILL mode:
            if ($dry) {
                $this->comment("  [DRY] Would backfill progress using LevelUnlockService based on attempts.");
                continue;
            }

            // If no progress exists, initialize. Otherwise backfill (recompute) safely.
            if (!$existing) {
                $progress = $service->initializeUserProgress($user);
                $user->update(['current_level_id' => $progress->current_level_id]);

                $this->info("  Initialized OK → current={$progress->current_level_id}, highest={$progress->highest_unlocked_level_id}");
            } else {
                // Rebuild based on completed levels: delete then create fresh backfill
                $existing->delete();

                $progress = $service->backfillUserProgress($user);

                // Keep users table in sync
                $user->update(['current_level_id' => $progress->current_level_id]);

                $this->info("  Backfill OK → current={$progress->current_level_id}, highest={$progress->highest_unlocked_level_id}");
            }
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
