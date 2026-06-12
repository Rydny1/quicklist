FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache \
    && touch database/database.sqlite

CMD php artisan migrate --force \
    && php artisan storage:link \
    && php artisan config:cache \
    && php -S 0.0.0.0:${PORT:-8080} -t public public/index.php
