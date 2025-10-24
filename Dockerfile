# Imagen base PHP
FROM php:8.2-cli

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev libpng-dev \
 && docker-php-ext-install pdo pdo_mysql zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Código
WORKDIR /app
COPY . /app

# Dependencias PHP (sin dev) y optimizaciones
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader || true

# IMPORTANTÍSIMO: permisos para el usuario 1000 (Render)
RUN chown -R 1000:1000 storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache

# Puerto que usará Render
ENV PORT=8080
EXPOSE 8080

# Ejecutar como usuario no-root (Render usa UID 1000)
USER 1000

# Arranque: migra y levanta servidor embebido de PHP
CMD php artisan migrate --force && php -S 0.0.0.0:${PORT} -t public
