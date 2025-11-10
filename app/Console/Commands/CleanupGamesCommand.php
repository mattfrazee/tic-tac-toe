<?php

namespace App\Console\Commands;

use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupGamesCommand extends Command
{
    protected $signature = 'games:cleanup
                            {--soft-days=1 : Soft delete threshold in days}
                            {--hard-days=7 : Hard delete threshold in days}
                            {--dry-run : Preview without deleting}
                            {--force : Skip confirmation prompt}';

    protected $description = 'Soft-delete games older than a configurable number of days and permanently delete games older than another configurable number of days.';

    public function handle(): int
    {
        $softDays = (int) $this->option('soft-days');
        $hardDays = (int) $this->option('hard-days');
        $dryRun  = $this->option('dry-run');
        $force   = $this->option('force');

        // Validate thresholds
        if ($softDays < 1) {
            $this->error('The --soft-days option must be an integer greater than or equal to 1.');
            return self::FAILURE;
        }
        if ($hardDays < 1) {
            $this->error('The --hard-days option must be an integer greater than or equal to 1.');
            return self::FAILURE;
        }

        $now = Carbon::now();

        // Determine thresholds
        $softDeleteThreshold = $now->copy()->subDays($softDays);
        $hardDeleteThreshold = $now->copy()->subDays($hardDays);

        // Query
        $softCandidates = Game::whereNull('deleted_at')
            ->where('created_at', '<', $softDeleteThreshold)
            ->get();

        $hardCandidates = Game::onlyTrashed()
            ->where('deleted_at', '<', $hardDeleteThreshold)
            ->get();

        if (! $softCandidates->count() && ! $hardCandidates->count()) {
            $this->info("\nNothing to delete.");
            return self::SUCCESS;
        }

        $this->info("🧹 Games Cleanup Summary:");
        $this->line("• Soft delete threshold: {$softDays} day(s)");
        $this->line("• Hard delete threshold: {$hardDays} day(s)");
        $this->line("• Soft-deletable: {$softCandidates->count()}");
        $this->line("• Hard-deletable: {$hardCandidates->count()}");

        if ($dryRun) {
            $this->warn("Dry run mode — no changes made.");
            return self::SUCCESS;
        }

        if (! $force && ! $this->confirm('Are you sure you want to proceed?')) {
            $this->info("Operation cancelled.");
            return self::SUCCESS;
        }

        // Softly delete
        $softDeleted = 0;
        foreach ($softCandidates as $game) {
            $game->delete();
            $softDeleted++;
        }

        // Hard delete
        $hardDeleted = 0;
        foreach ($hardCandidates as $game) {
            $game->forceDelete();
            $hardDeleted++;
        }

        $this->line("• Soft-deleted: {$softDeleted}");
        $this->line("• Permanently deleted: {$hardDeleted}");
        $this->info("✅ Cleanup complete!");

        return self::SUCCESS;
    }
}
