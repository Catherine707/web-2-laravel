# ---------- Etapa de assets (Node) ----------
FROM node:18-alpine AS assets
WORKDIR /app

# Archivos necesarios para dependencias y build
COPY package*.json vite.config.js ./

# Código fuente de frontend
COPY resources resources

# Instalar dependencias: usa ci si hay lock, si no install
RUN if [ -f package-lock.json ]; then \
      npm ci --no-audit --no-fund; \
    else \
      npm install --no-audit --no-fund; \
    fi

# Compilar assets (genera /app/public/build)
RUN npx vite build

# ---------- Etapa de PHP y dependencias ----------
FROM php:8.2-fpm-alpine AS php-deps

RUN apk add --no-cache \
    bash git libpng libpng-dev libjpeg-turbo-dev libwebp-dev oniguruma-dev \
    libzip-dev zip unzip icu-dev mariadb-client \
 && docker-php-ext-configure gd --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Dependencias PHP
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Copiar el build generado por Vite
COPY --from=assets /app/public/build ./public/build

# Permisos Laravel
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache public/build \
 && chmod -R 775 storage bootstrap/cache public/build

EXPOSE 8080
USER www-data
CMD php -S 0.0.0.0:8080 -t public

