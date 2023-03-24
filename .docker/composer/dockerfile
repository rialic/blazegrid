FROM php:7.4.30-fpm-alpine

# Enviorement
ENV TZ=America/Sao_Paulo

RUN apk --no-cache add vim tzdata shadow coreutils \
&& docker-php-ext-install pdo pdo_mysql \
&& rm -Rf /var/cache/apk/*

COPY --from=composer:2.3.9 /usr/bin/composer /usr/bin/composer