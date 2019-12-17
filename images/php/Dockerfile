FROM php:7.2-fpm-alpine

RUN set -ex \
  && apk --no-cache add \
    postgresql-dev

# lumen packages
RUN docker-php-ext-install mbstring tokenizer pdo pdo_pgsql