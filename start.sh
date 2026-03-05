#!/bin/bash
set -e

echo "==> Creating storage directories..."
mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "==> Running migrations..."
php artisan migrate --force || true

echo "==> Seeding database..."
php artisan db:seed --force || true

echo "==> Caching config..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "==> Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
