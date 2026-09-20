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

# Permisos de almacenamiento y creación de archivo sqlite
RUN mkdir -p database storage bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database

EXPOSE 80

CMD touch database/database.sqlite && php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=80
