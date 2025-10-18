#!/bin/bash
# ─────────────────────────────────────────────────────────────
# 🎵 Audio Compression Helper
# Converts one or more audio files with FFmpeg to compressed
# formats (default: mp3), appending "-small" to filenames.
# Original Command:
#   ffmpeg -i public/music/XXX.mp3 -b:a 96k -ar 44100 -ac 2 public/music/XXX-small.mp3
# Usage:
#   ./scripts/compress-audio.sh file1.mp3 file2.wav
# Options:
#   -f <format>   Output format (mp3, ogg, wav, etc.)
#   -b <bitrate>  Audio bitrate (default: 96k)
#   -r <rate>     Sample rate (default: 44100)
#   -c <channels> Channels (default: 2)
#   -j            Output JSON for Laravel/Vue usage and process all mp3 files
#   -J            Only regenerate JSON from existing compressed files
# ─────────────────────────────────────────────────────────────
set -e

# Default settings
format="mp3"
bitrate="96k"
samplerate="44100"
channels="2"
generate_json=false
process_all_mp3=false
regenerate_json_only=false

# Parse flags
while getopts ":f:b:r:c:jJ" opt; do
  case $opt in
    f) format="$OPTARG" ;;
    b) bitrate="$OPTARG" ;;
    r) samplerate="$OPTARG" ;;
    c) channels="$OPTARG" ;;
    j) generate_json=true; process_all_mp3=true ;;
    J) regenerate_json_only=true ;;
    \?) echo "Invalid option -$OPTARG" >&2; exit 1 ;;
  esac
done

shift $((OPTIND -1))

inputs=()

# Transform filename to title case with spaces
transform_key() {
  local key="$1"
  # Replace hyphens with spaces
  key="${key//-/ }"
  # Capitalize first letter of each word
  echo "$key" | awk '{for(i=1;i<=NF;i++){$i=toupper(substr($i,1,1)) substr($i,2)}}1'
}

# Function to generate JSON from given array of entries (name:path)
generate_json() {
  local entries=("$@")
  echo ""
  echo "🧠 Generated JSON for backgroundMusicFiles:"

  sorted_entries=($(printf '%s\n' "${entries[@]}" | sort))

  json_content="{"
  first=true
  for entry in "${sorted_entries[@]}"; do
    key="${entry%%:*}"
    value="${entry#*:}"
    transformed_key=$(transform_key "$key")
    if $first; then
      json_content+="\n  \"$transformed_key\": \"$value\""
      first=false
    else
      json_content+=",\n  \"$transformed_key\": \"$value\""
    fi
  done
  json_content+="\n}"

  echo -e "$json_content"

  script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
  json_file="$script_dir/../resources/js/data/musicFiles.json"

  echo -e "$json_content" > "$json_file"

  echo "✅  JSON saved to $json_file"
}

if $regenerate_json_only; then
  # Scan public/music/ for *-small.mp3 files (POSIX-compatible, no mapfile)
  files=()
  while IFS= read -r file; do
    files+=("$file")
  done < <(find public/music/ -maxdepth 1 -type f -name "*-small.mp3")
  json_entries=()
  for file in "${files[@]}"; do
    basefile=$(basename "$file")
    # Extract name by removing -small.mp3 suffix
    name="${basefile%-small.mp3}"
    json_entries+=("$name:/music/${name}-small.mp3")
  done
  generate_json "${json_entries[@]}"
  exit 0
fi

if $process_all_mp3; then
  # Process all mp3 files in resources/music/
  while IFS= read -r -d '' file; do
    inputs+=("$(basename "$file")")
  done < <(find resources/music/ -maxdepth 1 -type f -name "*.mp3" -print0)
else
  if [ $# -eq 0 ]; then
    echo "Usage: $0 [-f format] [-b bitrate] [-r rate] [-c channels] [-j] [-J] <file1> [file2 ...]"
    exit 1
  fi
  inputs=("$@")
fi

json_entries=()

for input in "${inputs[@]}"; do
  input_path="resources/music/$input"
  if [ ! -f "$input_path" ]; then
    echo "❌ File not found: $input_path"
    continue
  fi

  name="${input%.*}"
  output="public/music/${name}-small.$format"

  echo "🎧 Compressing: $input → $(basename "$output")"
  ffmpeg -i "$input_path" -b:a "$bitrate" -ar "$samplerate" -ac "$channels" "$output" -y >/dev/null 2>&1

  if [ -f "$output" ]; then
    echo "✅  Done: $output"
    json_entries+=("$name:/music/${name}-small.$format")
  else
    echo "⚠️ Failed to create: $output"
  fi
done

if $generate_json; then
  generate_json "${json_entries[@]}"
fi
