FROM php:8.3-fpm-alpine

RUN apk add --no-cache --virtual build-dependencies icu-dev libxml2-dev $PHPIZE_DEPS \
    && apk add --update linux-headers \
    && docker-php-ext-install -j$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) mysqli opcache pdo_mysql xml fileinfo pdo exif\
    && pecl install redis \
    && docker-php-ext-enable redis


