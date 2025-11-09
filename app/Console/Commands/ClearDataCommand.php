<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:clear
                            {--only=all : Limit what to clear (games|rooms|stats|all)}
                            {--force : Skip confirmation prompt and force delete}
                            {--dry-run : Preview what would be cleared without executing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear game-related data: games, moves, room codes, and player stats from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $only = $this->option('only');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        $validOptions = ['games', 'rooms', 'stats', 'all'];
        if (!in_array($only, $validOptions)) {
            $this->error("Invalid --only option value. Allowed values are: games, rooms, stats, all.");
            return self::FAILURE;
        }

        $tablesToClear = [];

        if ($only === 'all' || $only === 'games') {
            $tablesToClear[] = 'games';
            $tablesToClear[] = 'moves';
        }
        if ($only === 'all' || $only === 'rooms') {
            $tablesToClear[] = 'room_codes';
        }
        if ($only === 'all' || $only === 'stats') {
            $tablesToClear[] = 'player_stats';
        }

        $tablesToClear = array_unique($tablesToClear);

        $confirmMessage = "⚠️ This will delete data from the following tables: " . implode(', ', $tablesToClear) . ".\nAre you sure you want to continue?";

        if (!$force) {
            if (!$this->confirm($confirmMessage)) {
                $this->info('Operation cancelled.');
                return self::SUCCESS;
            }
        } else {
            $this->info($confirmMessage);
        }

        $this->info($dryRun ? 'Dry run mode: No changes will be made.' : 'Clearing data...');

        foreach ($tablesToClear as $table) {
            $count = DB::table($table)->count();
            if ($dryRun) {
                $this->line(" - {$table}: would delete {$count} record(s)");
            } else {
                DB::table($table)->truncate();
                $this->line(" - {$table}: deleted {$count} record(s)");
            }
        }

        $this->info('✅  Operation completed.');

        return self::SUCCESS;
    }
}
