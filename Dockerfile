FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash git libpng libpng-dev libjpeg-turbo-dev libwebp-dev oniguruma-dev \
    libzip-dev zip unzip icu-dev mariadb-client \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip \
 && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
 && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 8080
USER www-data
CMD php -S 0.0.0.0:8080 -t public
