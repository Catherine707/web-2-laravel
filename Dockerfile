FROM php:8.2-fpm-alpine

# PHP deps
RUN apk add --no-cache \
    bash git libpng libpng-dev libjpeg-turbo-dev libwebp-dev oniguruma-dev \
    libzip-dev zip unzip icu-dev mariadb-client \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip \
 && rm -rf /var/cache/apk/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 👇 Añadimos Node y npm para compilar Vite
RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html
COPY . .

# Instalar PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# 👇 Instalar deps de Node (incluye dev) y compilar assets
#    (si no tienes package-lock.json, npm install; si lo tienes, npm ci)
RUN if [ -f package-lock.json ]; then \
      npm ci --include=dev --no-audit --no-fund; \
    else \
      npm install --include=dev --no-audit --no-fund; \
    fi \
 && npm run build

# Permisos Laravel
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache public/build \
 && chmod -R 775 storage bootstrap/cache public/build

EXPOSE 8080
USER www-data
CMD php -S 0.0.0.0:8080 -t public
