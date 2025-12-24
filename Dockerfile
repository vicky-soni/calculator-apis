FROM php:8.2-apache

# Install mysqli (MySQL extension)
RUN docker-php-ext-install mysqli

WORKDIR /var/www/html

COPY . /var/www/html

EXPOSE 80
