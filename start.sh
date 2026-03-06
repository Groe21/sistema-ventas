#!/bin/bash

echo "==> Creating storage directories..."
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache

echo "==> Discovering packages..."
php artisan package:discover --ansi 2>&1 || true

echo "==> Clearing all caches..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true
php artisan cache:clear 2>&1 || true

echo "==> Running migrations..."
php artisan migrate --force 2>&1 || true

echo "==> Seeding database..."
php artisan db:seed --force 2>&1 || true

echo "==> Starting server on 0.0.0.0:${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
