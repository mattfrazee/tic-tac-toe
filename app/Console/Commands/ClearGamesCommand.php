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
    protected $signature = 'game:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all game and move data from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('moves')->truncate();
        DB::table('games')->truncate();
        $this->info('All game data cleared!');
    }
}
