FROM node:18-alpine AS assets
WORKDIR /app

COPY package*.json vite.config.js ./

COPY resources resources

RUN npm ci
RUN npm run build   # genera /app/public/build

# ---------- Etapa de PHP y dependencias ----------
FROM php:8.2-fpm-alpine AS php-deps

RUN apk add --no-cache \
    bash git libpng libpng-dev libjpeg-turbo-dev libwebp-dev oniguruma-dev \
    libzip-dev zip unzip icu-dev mariadb-client \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

COPY --from=assets /app/public/build ./public/build

RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache public/build \
 && chmod -R 775 storage bootstrap/cache public/build

EXPOSE 8080
USER www-data
CMD php -S 0.0.0.0:8080 -t public
