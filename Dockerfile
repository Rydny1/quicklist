FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN php artisan config:cache && php artisan route:cache

CMD php artisan migrate --force && php -S 0.0.0.0:${PORT:-8080} -t public public/index.php
