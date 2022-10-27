FROM nginx:1.23.1-alpine as Builder

ARG HEADERS_MORE_MODULE_VERSION=0.34

RUN apk --no-cache add gcc make linux-headers pcre-dev openssl-dev libc-dev zlib-dev libxslt-dev gd-dev geoip-dev \
&& NGINX_VERSION=$(nginx -v 2>&1 | sed 's/^[^0-9]*//') \
&& NGINX_CONFIGURE_ARGS=$(nginx -V 2>&1 | tail -1 | sed -e 's/configure arguments://' -e "s/ --with-cc-opt='-Os -fomit-frame-pointer -g'//") \
&& NGINX_MODULE__HEADERS_MORE_FILTER="/usr/src/headers-more-nginx-module-${HEADERS_MORE_MODULE_VERSION}" \
&& wget "http://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz" -O nginx.tar.gz \
&& wget "https://github.com/openresty/headers-more-nginx-module/archive/refs/tags/v${HEADERS_MORE_MODULE_VERSION}.tar.gz" -O headers-more.tar.gz \
&& mkdir -p /usr/src \
&& tar -zxC /usr/src -f "nginx.tar.gz" \
&& tar -zxC /usr/src -f "headers-more.tar.gz" \
&& cd /usr/src/nginx-${NGINX_VERSION} \
&& ./configure --without-http_autoindex_module ${NGINX_CONFIGURE_ARGS} --add-dynamic-module=${NGINX_MODULE__HEADERS_MORE_FILTER} \
&& make \
&& make install

FROM nginx:1.23.1-alpine as Main

COPY --from=Builder /etc/nginx/modules/ngx_http_headers_more_filter_module.so /etc/nginx/modules/ngx_http_headers_more_filter_module.so

# Enviorement
ENV TZ=America/Sao_Paulo

RUN apk --no-cache add vim tzdata \
&& mkdir -p /etc/nginx/certs/mkcert

ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./server.conf /etc/nginx/conf.d/server.conf
ADD ./certs/ /etc/nginx/certs/mkcert