FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install production deps only
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Prepare directories
RUN mkdir -p storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && chmod +x start.sh

# Remove any stale dev-package caches
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php

EXPOSE 8080

CMD ["bash", "start.sh"]
