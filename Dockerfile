FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache nginx supervisor curl git postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html

# Copiar código del proyecto
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Permisos de almacenamiento
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD php artisan migrate --force && php artisan config:cache && php artisan serve --host=0.0.0.0 --port=80
