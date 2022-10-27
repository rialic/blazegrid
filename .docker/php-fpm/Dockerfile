FROM php:7.4.30-fpm-alpine

# Enviorement
ENV TZ=America/Sao_Paulo

RUN apk --no-cache add vim tzdata shadow coreutils curl sudo \
&& docker-php-ext-install pdo pdo_mysql \
&& rm -Rf /var/cache/apk/*