FROM alpine:3.15.4

RUN apk --no-cache add php7 \
    php7-common \
    php7-fpm \
    php7-pdo \
    php7-opcache \
    php7-zip \
    php7-phar \
    php7-iconv \
    php7-cli \
    php7-curl \
    php7-openssl \
    php7-mbstring \
    php7-tokenizer \
    php7-fileinfo \
    php7-json \
    php7-xml \
    php7-xmlwriter \
    php7-simplexml \
    php7-dom \
    php7-tokenizer \
    php7-pecl-redis \
&& rm -Rf /var/cache/apk/*

COPY --from=composer:2.3.9 /usr/bin/composer /usr/bin/composer