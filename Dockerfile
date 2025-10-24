# Imagen con Nginx + PHP-FPM lista para producción
FROM webdevops/php-nginx:8.3-alpine

# Directorio público de Laravel
ENV WEB_DOCUMENT_ROOT=/app/public

# Ajustes PHP
ENV PHP_DISPLAY_ERRORS=0 \
    PHP_MEMORY_LIMIT=256M \
    PHP_POST_MAX_SIZE=50M \
    PHP_UPLOAD_MAX_FILESIZE=50M

# Paquetes y extensiones PHP necesarias
RUN apk add --no-cache bash git icu-dev oniguruma-dev libzip-dev nodejs npm mysql-client $PHPIZE_DEPS \
 && docker-php-ext-install pdo_mysql intl opcache zip

WORKDIR /app

COPY . /app

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# Compila assets (Vite). Si NO usas Vite, comenta estas 2 líneas.
RUN npm ci && npm run build

# Permisos para Laravel
RUN mkdir -p /app/storage/framework/{cache,sessions,views} /app/storage/logs \
 && chown -R application:application /app/storage /app/bootstrap/cache /app/public/build

USER application


RUN php artisan key:generate --force || true \
 && php artisan config:cache && php artisan route:cache && php artisan view:cache


CMD ["bash","-lc","php artisan migrate --force && /usr/bin/supervisord -n"]
