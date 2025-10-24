# Etapa base: PHP CLI + extensiones necesarias
FROM php:8.3-cli-alpine AS app

# Instala dependencias del sistema y extensiones de PHP
RUN apk add --no-cache \
    bash curl git unzip icu-dev libzip-dev oniguruma-dev mariadb-connector-c-dev \
 && docker-php-ext-install pdo_mysql intl zip opcache

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copia composer.* y luego el resto del código (mejora el cache de capas)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Ahora sí, copia el resto del proyecto
COPY . .

# Permisos para que Laravel pueda escribir
RUN chmod -R ug+rwx storage bootstrap/cache

# Opcional: compilar assets Vite si los tienes listos en package.json
# (Descomenta si quieres construir frontend dentro del contenedor)
# RUN apk add --no-cache nodejs npm
# RUN npm ci && npm run build

# Laravel necesita saber en qué puerto escuchar. Render expone $PORT.
ENV PORT=10000

# Comando de arranque (limpia caches y arranca servidor PHP embebido)
CMD php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear \
 && php -S 0.0.0.0:${PORT} -t public
