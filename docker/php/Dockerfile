FROM php:7-fpm

RUN apt-get update && apt-get install -y \
        git zip unzip zlib1g-dev \
    && docker-php-ext-install -j$(nproc) zip

WORKDIR /quarto
