<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserProgress;
use App\Services\LevelUnlockService;
use Illuminate\Console\Command;

class BackfillUserProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:backfill-progress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill user_progress for existing users based on their attempts';

    /**
     * Execute the console command.
     */
    public function handle(LevelUnlockService $unlockService)
    {
        $this->info('Starting user progress backfill...');

        // Get users without progress records
        $users = User::whereDoesntHave('progress')->get();

        if ($users->isEmpty()) {
            $this->info('No users need backfilling.');
            return 0;
        }

        $this->info("Found {$users->count()} users without progress records.");

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            try {
                $unlockService->backfillUserProgress($user);
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nFailed to backfill user {$user->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();
        $this->info('User progress backfill completed!');

        return 0;
    }
}
