<?php

namespace App\Console\Commands;

use App\Models\PlayerStat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GameStatsReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:stats-report
        {--limit=10 : Number of top players to display}
        {--export= : Export format (json|csv)}
        {--path=storage/app/reports/leaderboard.json : Output path for export file}
        {--include-bots : Include computer players in the report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '📊 Display a leaderboard of player statistics, with optional export to JSON or CSV.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $limit = (int)$this->option('limit');
        $export = $this->option('export');
        $path = base_path($this->option('path'));
        $includeBots = $this->option('include-bots');

        $this->info('🎮  Generating Player Stats Report...');

        // Build query
        $query = PlayerStat::query()
            ->when(!$includeBots, fn($q) => $q->where('is_computer', false))
            ->where('games_played', '>', 0)
            ->orderByRaw('(games_won * 1.0 / NULLIF(games_played, 0)) DESC')
            ->orderByDesc('games_won')
            ->orderByDesc('games_played')
            ->limit($limit);

        $players = $query->get();

        if ($players->isEmpty()) {
            $this->warn("\nNo players found.");
            return;
        }

        // Display console table
        $this->table(
            ['#', 'Player', 'Played', 'Won', 'Lost', 'Win %', 'Loss %', 'Bot?'],
            $players->map(function ($p, $i) {
                return [
                    $i + 1,
                    $p->name,
                    $p->games_played,
                    $p->games_won,
                    $p->games_lost,
                    number_format($p->win_percentage, 1),
                    number_format($p->loss_percentage, 1),
                    $p->is_computer ? '🤖' : '👤',
                ];
            })
        );

        // Optional export
        if ($export) {
            $data = $players->map(fn($p) => [
                'rank' => $p->rank ?? null,
                'name' => $p->name,
                'games_played' => $p->games_played,
                'games_won' => $p->games_won,
                'games_lost' => $p->games_lost,
                'win_percentage' => $p->win_percentage,
                'loss_percentage' => $p->loss_percentage,
                'is_computer' => $p->is_computer,
            ])->values()->toArray();

            File::ensureDirectoryExists(dirname($path));

            match ($export) {
                'json' => File::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)),
                'csv' => File::put($path, $this->toCsv($data)),
                default => $this->error("Unsupported export format: {$export}")
            };

            $this->info("✅ Exported stats to {$path}");
        }
    }

    /**
     * Convert an array of associative arrays to CSV.
     */
    private function toCsv(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $headers = array_keys($data[0]);
        $lines = [implode(',', $headers)];

        foreach ($data as $row) {
            $lines[] = implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row));
        }

        return implode("\n", $lines);
    }
}
