FROM php:7.4-apache

MAINTAINER hiromitsu matsuoka <h.matsuoka@wiz-net.jp>

COPY .docker-compose.d/app/php.ini /usr/local/etc/php/conf.d/php.ini
RUN apt-get update && \
    apt-get install -y libmemcached-dev libz-dev libzip-dev libjpeg-dev libpng-dev libmcrypt-dev libfreetype6-dev libonig-dev vim && \
    docker-php-ext-configure gd \
            --with-freetype=/usr/include/freetype2 \
            --with-jpeg=/usr/include && \
        docker-php-ext-install gd && \
    docker-php-ext-install pdo_mysql mysqli mbstring

RUN a2enmod rewrite
RUN a2enmod headers
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN pecl install memcached && docker-php-ext-enable memcached
RUN pecl install zip && docker-php-ext-enable zip
RUN pecl install mcrypt && docker-php-ext-enable mcrypt

WORKDIR /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]

