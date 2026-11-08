FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql


RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html
