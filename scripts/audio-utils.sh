#!/bin/bash
# ─────────────────────────────────────────────────────────────
# 🎧 Audio Utils
# Lightweight helper for Laravel Artisan command to compress
# audio files using FFmpeg.
# Usage:
#   ./scripts/audio-utils.sh <input_file> <output_file> [bitrate] [sample_rate] [channels] [overwrite]
# Example:
#   ./scripts/audio-utils.sh resources/music/song.mp3 public/music/song-small.mp3 96k 44100 2 true
# ─────────────────────────────────────────────────────────────

input="$1"
output="$2"
bitrate="${3:-96k}"
sample_rate="${4:-44100}"
channels="${5:-2}"
overwrite="${6:-false}"

if [ ! -f "$input" ]; then
  echo "❌ File not found: $input"
  exit 1
fi

if [ -f "$output" ] && [ "$overwrite" != "true" ]; then
  echo "⚠️  Skipping: $output already exists."
  exit 0
fi

echo "🎧 Compressing: $(basename "$input") → $(basename "$output")"
ffmpeg -i "$input" -b:a "$bitrate" -ar "$sample_rate" -ac "$channels" "$output" -y >/dev/null 2>&1

if [ -f "$output" ]; then
  echo "✅  Done: $output"
  exit 0
else
  echo "⚠️  Failed to create: $output"
  exit 1
fi
