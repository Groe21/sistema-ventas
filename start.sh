#!/bin/bash
set -e

echo "==> Checking APP_KEY..."
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:PLACEHOLDER" ]; then
    echo "WARNING: APP_KEY not set or is placeholder. Generating new key..."
    php artisan key:generate --force --show > /tmp/key.txt 2>&1 || echo "Failed to generate key"
fi

echo "==> Creating storage directories..."
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Clearing caches..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true
php artisan cache:clear 2>&1 || true

echo "==> Optimizing configuration..."
php artisan config:cache 2>&1 || true

echo "==> Running migrations..."
if php artisan migrate --force 2>&1; then
    echo "✓ Migrations completed successfully"
else
    echo "⚠ Migrations failed or skipped"
fi

echo "==> Checking if database needs seeding..."
if [ "$RUN_SEEDS" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force 2>&1 || echo "Seeding failed or skipped"
else
    echo "Skipping seeds (set RUN_SEEDS=true to enable)"
fi

echo "==> Starting server on 0.0.0.0:${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}" --no-reload
