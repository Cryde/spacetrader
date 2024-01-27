FROM php:8.3-fpm-alpine3.19

RUN apk add --update --no-cache npm

RUN apk add --update --no-cache --repository=https://dl-cdn.alpinelinux.org/alpine/edge/community \
    rabbitmq-c-dev

RUN mkdir -p /usr/src/php/ext/amqp &&   \
    curl -fsSL https://pecl.php.net/get/amqp | tar xvz -C "/usr/src/php/ext/amqp" --strip 1


RUN docker-php-ext-install amqp