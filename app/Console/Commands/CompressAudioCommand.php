<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class CompressAudioCommand extends Command
{
    protected $signature = 'audio:compress
        {--source=resources/music : Source folder}
        {--destination=public/music : Output folder}
        {--format=mp3 : Output format}
        {--bitrate=96k : Bitrate}
        {--sample-rate=44100 : Sample rate}
        {--channels=2 : Channels}
        {--json : Generate JSON output}
        {--dry-run : Preview actions only}
        {--overwrite : Overwrite existing files if they exist}
        {--json-path=resources/js/data/musicFiles.json : Custom output path for JSON file}
        {--json-only : Generate JSON output only (skip compression)}';

    protected $description = '🎵 Compress audio files using FFmpeg and optionally regenerate JSON list.';

    public function handle(): void
    {
        $source = base_path($this->option('source'));
        $destination = base_path($this->option('destination'));
        $format = $this->option('format');
        $bitrate = $this->option('bitrate');
        $sampleRate = $this->option('sample-rate');
        $channels = $this->option('channels');
        $dryRun = $this->option('dry-run');
        $generateJson = $this->option('json');
        $overwrite = $this->option('overwrite');
        $jsonPath = base_path($this->option('json-path'));
        $jsonOnly = $this->option('json-only');
        if ($jsonOnly) {
            $generateJson = true;
        }

        if (!$jsonOnly) {
            $this->info("🎧 Scanning: {$source}");
            $files = File::glob("{$source}/*.{mp3,wav,ogg}", GLOB_BRACE);

            if (empty($files)) {
                $this->warn("No audio files found.");
                return;
            }

            $processed = [];
            foreach ($files as $file) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $output = "{$destination}/{$name}.{$format}";
//                $output = "{$destination}/{$name}-small.{$format}";

                if (File::exists($output) && !$overwrite) {
                    $this->warn("⚠️ Skipped (exists): {$output}");
                    continue;
                }

                if ($dryRun) {
                    $this->line("Would compress: {$file} → {$output}");
                    continue;
                }

                $cmd = [
                    'ffmpeg', '-i', $file,
                    '-b:a', $bitrate,
                    '-ar', $sampleRate,
                    '-ac', $channels,
                ];

                if ($overwrite) {
                    $cmd[] = '-y';
                }

                $cmd[] = $output;

                $process = new Process($cmd);
                $process->run();

                if ($process->isSuccessful()) {
                    $this->info("✅  {$name} compressed successfully.");
                    $processed[$name] = [
                        'path' => str_replace(public_path(), '', $output),
                        'format' => $format,
                        'bitrate' => $bitrate,
                        'sampleRate' => (int) $sampleRate,
                        'channels' => (int) $channels,
                        'sizeKB' => round(File::size($output) / 1024, 1),
                    ];
                } else {
                    $this->error("❌ Failed: {$file}");
                    $this->line($process->getErrorOutput());
                }
            }
            $this->info(($dryRun ? 'Would process ' : 'Processed ') . count($processed) . ' file(s).');
        }

        if ($generateJson && !$dryRun) {
            $this->info("🧠 Generating JSON → {$jsonPath}");

            $jsonData = [];
            $audioFiles = File::glob("{$destination}/*.{mp3,wav,ogg}", GLOB_BRACE);

            foreach ($audioFiles as $audioFile) {
                $filename = pathinfo($audioFile, PATHINFO_FILENAME);
                // Normalize song name: replace '-' with spaces and capitalize each word
                $songName = ucwords(str_replace('-', ' ', $filename));

                $ext = pathinfo($audioFile, PATHINFO_EXTENSION);

                $jsonData[$songName] = [
                    'path' => str_replace(public_path(), '', $audioFile),
                    'format' => $ext,
                    'bitrate' => $bitrate,
                    'sampleRate' => (int) $sampleRate,
                    'channels' => (int) $channels,
                    'sizeKB' => round(File::size($audioFile) / 1024, 1),
                ];
            }

            $json = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            File::put($jsonPath, $json);

            $this->info("✅  JSON updated at {$jsonPath} with " . count($jsonData) . " tracks.");
        }
    }
}
