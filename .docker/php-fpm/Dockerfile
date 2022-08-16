FROM php:7.4-fpm-alpine

# Arguments defined in docker-compose.yml
ARG GROUP
ARG UID

# Enviorement
ENV TZ=America/Sao_Paulo

RUN apk --no-cache add vim zip unzip curl tzdata shadow \
&& docker-php-ext-install pdo pdo_mysql \
&& usermod -u $UID $GROUP \
&& rm -Rf /var/cache/apk/*