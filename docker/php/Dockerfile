FROM php:7-fpm

RUN apt-get update && apt-get install -y \
        curl git zip unzip zlib1g-dev && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    docker-php-ext-install -j$(nproc) zip

WORKDIR /quarto
