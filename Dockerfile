FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP (PostgreSQL + SQLite)
RUN apk add --no-cache nginx supervisor curl git postgresql-dev sqlite-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_sqlite

# Copiar ejecutable oficial de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar código del proyecto
COPY . .

# Instalar dependencias con Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Permisos de almacenamiento
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 80

CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=80
