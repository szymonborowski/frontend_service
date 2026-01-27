FROM php:8.5-fpm AS php-runtime

ARG UID=1000
ARG GID=1000

RUN groupadd -g ${GID} app \
 && useradd -u ${UID} -g app -m app

RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath intl \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ../../../src .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-ansi \
    --no-scripts

RUN rm -f bootstrap/cache/*.php

# Then run the Laravel step explicitly
RUN php artisan package:discover --ansi

RUN chown -R www-data:www-data storage bootstrap/cache

RUN php artisan view:clear \
 && php artisan config:clear \
 && php artisan route:clear

USER app