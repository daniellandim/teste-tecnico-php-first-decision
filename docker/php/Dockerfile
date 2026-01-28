FROM php:8.4-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libzip-dev unzip \
    && docker-php-ext-install pdo pdo_pgsql zip bcmath

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
