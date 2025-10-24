FROM php:8.2-fpm-alpine AS php-deps

RUN apk add --no-cache \
    bash git libpng libpng-dev libjpeg-turbo-dev libwebp-dev oniguruma-dev \
    libzip-dev zip unzip icu-dev mariadb-client \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-scripts --optimize-autoloader



FROM node:20-alpine AS assets
WORKDIR /app


COPY package*.json vite.config.js ./

COPY tailwind.config.* postcss.config.* ./ 2>/dev/null || true

COPY resources resources

RUN npm ci
RUN npm run build   # <-- genera public/build/manifest.json


FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    bash git libpng libpng-dev libjpeg-turbo-dev libwebp-dev oniguruma-dev \
    libzip-dev zip unzip icu-dev mariadb-client \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html


COPY . .


COPY --from=php-deps /var/www/html/vendor /var/www/html/vendor

COPY --from=assets /app/public/build /var/www/html/public/build

RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache public/build \
 && chmod -R 775 storage bootstrap/cache public/build

USER www-data

RUN php artisan config:cache || true \
 && php artisan route:cache  || true \
 && php artisan view:cache   || true

EXPOSE 8080
CMD ["php","-S","0.0.0.0:8080","-t","public"]
