FROM php:7.4.30-fpm-alpine

# Enviorement
ENV TZ=America/Sao_Paulo

RUN apk --no-cache add vim tzdata shadow coreutils curl \
&& docker-php-ext-install pdo pdo_mysql \
&& addgroup -S -g 1000 dok \
&& adduser -S -u 1000 -g dok dok \
&& rm -Rf /var/cache/apk/*

USER dok:dok