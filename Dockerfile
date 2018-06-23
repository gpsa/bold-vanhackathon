FROM php:7.2-apache

RUN apt-get update \
 && apt-get install -y git zlib1g-dev \
 && docker-php-ext-install zip \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

# Changes UID to 1000
RUN usermod -u 1000 www-data


# MySQL
RUN docker-php-ext-install pdo pdo_mysql

# PHP-RABBITMQ
RUN apt-get install -y \
        librabbitmq-dev \
        libssh-dev \
    && docker-php-ext-install \
        bcmath \
        sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# PHP-REDIS
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# PHP-INTL
RUN apt-get install -y libicu-dev\
        && docker-php-ext-configure intl \
        && docker-php-ext-install intl

WORKDIR /var/www
