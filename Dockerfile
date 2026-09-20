FROM php:8.3-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache nginx supervisor curl git postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar ejecutable oficial de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar código del proyecto
COPY . .

# Instalar dependencias con Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Permisos de almacenamiento
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=80
