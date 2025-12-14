# ⚙️ Maatify Bootstrap — Docker Build
FROM php:8.4-cli

LABEL maintainer="Maatify.dev <support@maatify.dev>"
LABEL project="maatify/bootstrap"

# System dependencies
RUN apt-get update && apt-get install -y \
    git zip unzip curl libicu-dev libonig-dev libzip-dev && \
    docker-php-ext-install intl mbstring zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Project files
WORKDIR /app
COPY . /app

RUN composer install --no-interaction --prefer-dist --no-progress

# Default command
CMD ["vendor/bin/phpunit", "--testdox"]
