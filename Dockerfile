FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets \
    && docker-php-ext-enable gd

# SQLite support (bundled with PHP, just enable)
RUN docker-php-ext-install pdo_sqlite

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN touch database/database.sqlite \
    && composer install --no-interaction --optimize-autoloader --no-dev \
    && php artisan storage:link \
    && php artisan key:generate --force \
    && php artisan migrate --force \
    && chown -R www-data:www-data storage bootstrap/cache public/storage database \
    && chmod -R 775 storage bootstrap/cache database

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install --ignore-scripts \
    && npm run build \
    && rm -rf node_modules

EXPOSE 9000
CMD ["php-fpm"]
