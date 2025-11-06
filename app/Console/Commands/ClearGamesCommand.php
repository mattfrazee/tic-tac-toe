<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearGamesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:clear {--force : Skip confirmation prompt and force delete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all games, moves and player stats data from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->option('force')) {
            if (! $this->confirm('⚠️  This will delete ALL games, moves and player stats. Are you sure you want to continue?')) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
        }

        DB::table('moves')->truncate();
        DB::table('games')->truncate();
        DB::table('player_stats')->truncate();
        $this->info('✅ All games, moves, and player stats have been removed successfully.');

        return self::SUCCESS;
    }
}
