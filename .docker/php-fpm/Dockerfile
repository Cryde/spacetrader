FROM php:8.3-fpm-alpine3.19

RUN apk add --update --no-cache npm

RUN apk add --update --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    rabbitmq-c-dev \
    build-base autoconf php83-pecl-igbinary php83-pecl-redis \
    libpq-dev php83-pgsql php83-pdo_pgsql

RUN mkdir -p /usr/src/php/ext/amqp &&   \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1

RUN docker-php-ext-install amqp opcache pdo pgsql pdo_pgsql

RUN pecl install igbinary redis && docker-php-ext-enable igbinary redis

ADD 99-custom.ini /usr/local/etc/php/conf.d