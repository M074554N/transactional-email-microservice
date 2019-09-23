FROM php:7.3-fpm-alpine

COPY public /var/www/public
COPY composer.lock composer.json /var/www/

WORKDIR /var/www

RUN apk update && apk add --no-cache \
    build-base shadow vim curl \
    php7 \
    php7-fpm \
    php7-common \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-mcrypt \
    php7-mbstring \
    php7-xml \
    php7-openssl \
    php7-json \
    php7-phar \
    php7-zip \
    php7-gd \
    php7-dom \
    php7-session \
    php7-zlib

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www

RUN chmod -R 777 storage && chmod -R 777 bootstrap/cache

RUN composer install && php artisan key:generate

RUN php artisan migrate

EXPOSE 9000

CMD ["php-fpm"]