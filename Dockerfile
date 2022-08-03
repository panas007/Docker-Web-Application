FROM php:7.4.11-apache

COPY /Application_src /var/www/html/

RUN apt-get update && apt-get install --yes \
    libssl-dev

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb



