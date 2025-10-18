#!/bin/bash
# ──────────────────────────────────────────────
# 🚀 Laravel + Vue + Vite Deploy Script (No Build on Server)
# Usage: ./scripts/deploy.sh [--migrate]
# ──────────────────────────────────────────────

# === CONFIGURATION ===
USER="mattfrazee"
HOST=10.0.0.100
URL="https://tic-tac-toe.mattfrazee.com"
LOCAL_IP=$(node -p "Object.values(require('os').networkInterfaces()).flat().find(i => i.family === 'IPv4' && !i.internal)?.address || '127.0.0.1'")
LOCAL_URL="http://$LOCAL_IP:8000"
REMOTE_DIR="/var/www/html/tic-tac-toe"
LOCAL_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE=".env.production"
#SSH_KEY="~/.ssh/id_rsa"
SSH_KEY=""
RUN_MIGRATIONS=false
CI_MODE=false

cd "$LOCAL_DIR" || exit 1

# === DRY RUN FLAG ===
DRY_RUN=false
if [ "$1" = "--dry-run" ]; then
  DRY_RUN=true
  echo "🟡 Dry run mode enabled. Rsync will be simulated and no remote commands will be executed."
  RSYNC_FLAGS="-avzn"
else
  RSYNC_FLAGS="-avz"
fi

# === DETERMINE SSH COMMAND ===
if [ -n "$SSH_KEY" ]; then
  SSH_CMD="ssh -i $SSH_KEY"
  RSYNC_SSH="ssh -i $SSH_KEY"
else
  SSH_CMD="ssh"
  RSYNC_SSH="ssh"
fi

# === PROCESS FLAGS ===
for arg in "$@"
do
  case $arg in
    --migrate)
      RUN_MIGRATIONS=true
      shift
    ;;
    --ci)
      CI_MODE=true
      shift
    ;;
    --dry-run)
      shift
    ;;
    *)
    ;;
  esac
done

echo "──────────────────────────────────────────────"
echo "🏗️  Building frontend (Vite)..."
echo "──────────────────────────────────────────────"

if [ "$CI_MODE" = true ]; then
  echo "⚙️ CI Mode: Installing only production dependencies..."
  npm ci || { echo "❌ npm ci failed"; exit 1; }
else
  echo "⚙️ Local Mode: Installing all dependencies (including dev)..."
  npm install || { echo "❌ npm install failed"; exit 1; }
fi

npm run build || { echo "❌ Build failed"; exit 1; }

echo ""
echo "📦 Preparing deployment package..."
echo "──────────────────────────────────────────────"
rsync "$RSYNC_FLAGS" \
  --exclude=".env.production" \
  --exclude=".git" \
  --exclude="node_modules" \
  --exclude="tests" \
  --exclude="storage/logs/*" \
  --exclude="storage/framework/sessions/*" \
  --exclude="storage/framework/cache/*" \
  --exclude="storage/framework/views/*" \
  --exclude="*.sh" \
  --exclude="*.md" \
  --exclude="vite.config.*" \
  --exclude="package*.json" \
  --exclude="tailwind.config.*" \
  --exclude="postcss.config.*" \
  --exclude="tsconfig.*" \
  -e "$RSYNC_SSH" "$LOCAL_DIR/" "$USER@$HOST:$REMOTE_DIR" || { echo "❌ Rsync failed"; exit 1; }

if [ "$DRY_RUN" = true ]; then
  echo ""
  echo "🟡 Dry run: Skipping remote commands (env sync, cleanup, composer, cache, migrations)..."
else
  echo ""
  echo "🌿 Syncing environment file..."
  echo "──────────────────────────────────────────────"
  $SSH_CMD "$USER@$HOST" "cd $REMOTE_DIR && \
  if [ -f $ENV_FILE ]; then cp $ENV_FILE .env && echo '✅  Updated .env from $ENV_FILE'; else echo '⚠️  $ENV_FILE not found'; fi"

  echo ""
  echo "🧹 Cleaning dev/editor files on server..."
  echo "──────────────────────────────────────────────"
  $SSH_CMD "$USER@$HOST" "cd $REMOTE_DIR && rm -rf .git .github .editorconfig .idea node_modules tests vite.config.* package*.json tailwind.config.* postcss.config.*"

  echo ""
  echo "🔧 Running Composer optimization..."
  echo "──────────────────────────────────────────────"
  $SSH_CMD "$USER@$HOST" "cd $REMOTE_DIR && composer install --no-dev --optimize-autoloader"

  echo ""
  echo "⚙️  Caching Laravel configs..."
  echo "──────────────────────────────────────────────"
  $SSH_CMD "$USER@$HOST" "cd $REMOTE_DIR && php artisan config:cache && php artisan route:cache && php artisan view:cache"

  if [ "$RUN_MIGRATIONS" = true ]; then
    echo ""
    echo "🧩 Running migrations..."
    echo "──────────────────────────────────────────────"
    ssh -i "$SSH_KEY" "$USER@$HOST" "cd $REMOTE_DIR && php artisan migrate --force"
  fi
fi

echo ""
if [ "$DRY_RUN" = true ]; then
  echo "✅  Dry run complete! No files changed or remote commands executed."
else
  echo "✅  Deployment complete!"
fi

echo ""
echo "🔗 Application URL's"
echo "──────────────────────────────────────────────"
echo "Local Development: $LOCAL_URL"
echo "Production:        $URL"
echo "──────────────────────────────────────────────"
