FROM php:7.3-fpm

WORKDIR /var/www/html

COPY . /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
