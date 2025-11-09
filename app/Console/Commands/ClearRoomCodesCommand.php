<?php

namespace App\Console\Commands;

use App\Models\RoomCode;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ClearRoomCodesCommand extends Command
{
    protected $signature = 'room-codes:clear {--force : Skip confirmation prompt}';

    protected $description = 'Delete expired Tic Tac Toe room codes that are past their expiration date.';

    public function handle(): int
    {
        $this->info('🧹 Cleaning up expired Tic Tac Toe room codes...');
        $this->newLine();

        // Confirm before continuing (unless forced)
        if (! $this->option('force')) {
            if (! $this->confirm('Are you sure you want to delete expired room codes?')) {
                $this->warn('🛑 Operation cancelled.');
                return CommandAlias::SUCCESS;
            }
        }

        try {
            $expiredRooms = RoomCode::wherePast('expires_on');
            $count = $expiredRooms->count();

            if ($count === 0) {
                $this->info('✅  No expired room codes found.');
                return CommandAlias::SUCCESS;
            }
            $this->info("{$count} expired room code(s) found.");

            $expiredRooms->delete();

            $this->newLine();
            $this->components->info("Deleted {$count} expired room code(s) successfully!");
            $this->newLine();
            $this->line('🕓 Timestamp: ' . Carbon::now()->toDateTimeString());
        } catch (\Throwable $e) {
            $this->error('❌ Error while deleting expired rooms:');
            $this->line($e->getMessage());
            return CommandAlias::FAILURE;
        }

        return CommandAlias::SUCCESS;
    }
}
