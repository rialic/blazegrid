FROM nginx:1.23.1-alpine as Builder

ARG HEADERS_MORE_MODULE_VERSION=0.34

# INSTALL MODULES
RUN apk add gcc make linux-headers pcre-dev openssl-dev libc-dev zlib-dev libxslt-dev gd-dev git libmaxminddb libmaxminddb-dev \
# NGINX VERSION
&& NGINX_VERSION=$(nginx -v 2>&1 | sed 's/^[^0-9]*//') \
# NGINX DEFAULT CONFIGS
&& NGINX_CONFIGURE_ARGS=$(nginx -V 2>&1 | tail -1 | sed -e 's/configure arguments://' -e "s/ --with-cc-opt='-Os -fomit-frame-pointer -g'//") \
&& NGINX_MODULE__HEADERS_MORE_FILTER="/usr/src/headers-more-nginx-module-${HEADERS_MORE_MODULE_VERSION}" \
&& NGINX_MODULE__NGX_HTTP_GEOIP2_MODULE="/usr/src/ngx_http_geoip2_module" \
# START INSTALATION WITH MODULES
&& wget "http://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz" -O nginx.tar.gz \
&& wget "https://github.com/openresty/headers-more-nginx-module/archive/refs/tags/v${HEADERS_MORE_MODULE_VERSION}.tar.gz" -O headers-more.tar.gz \
&& mkdir -p /usr/src \
&& tar -zxC /usr/src -f "nginx.tar.gz" \
&& tar -zxC /usr/src -f "headers-more.tar.gz" \
&& git clone https://github.com/leev/ngx_http_geoip2_module.git ${NGINX_MODULE__NGX_HTTP_GEOIP2_MODULE} \
&& cd /usr/src/nginx-${NGINX_VERSION} \
&& ./configure --without-http_autoindex_module ${NGINX_CONFIGURE_ARGS} --add-dynamic-module=${NGINX_MODULE__HEADERS_MORE_FILTER} --add-dynamic-module=${NGINX_MODULE__NGX_HTTP_GEOIP2_MODULE} \
&& make \
&& make install \
&& mkdir -p /etc/nginx/sites-available \
&& mkdir -p /etc/nginx/sites-enabled

ADD ./default.conf /etc/nginx/sites-available/default.conf
ADD ./blazegrids.conf /etc/nginx/sites-available/blazegrids.conf

# INSTALL NGINX BAD BOT BLOCKER
RUN mkdir -p /usr/local/sbin \
&& cd /usr/local/sbin \
&& wget https://raw.githubusercontent.com/mitchellkrogza/nginx-ultimate-bad-bot-blocker/master/install-ngxblocker -O /usr/local/sbin/install-ngxblocker \
&& chmod 700 install-ngxblocker \
&& ./install-ngxblocker -x \
&& ./setup-ngxblocker -xe .conf \
&& ./update-ngxblocker

FROM nginx:1.23.1-alpine as Main

COPY --from=Builder /etc/nginx/modules/ngx_http_headers_more_filter_module.so /etc/nginx/modules/ngx_http_headers_more_filter_module.so
COPY --from=Builder /etc/nginx/modules/ngx_http_geoip2_module.so /etc/nginx/modules/ngx_http_geoip2_module.so
COPY --from=Builder /etc/nginx/conf.d /etc/nginx/conf.d
COPY --from=Builder /etc/nginx/bots.d /etc/nginx/bots.d
COPY --from=Builder /usr/local/sbin /usr/local/sbin
COPY --from=Builder /etc/nginx/sites-available /etc/nginx/sites-available
COPY --from=Builder /etc/nginx/sites-enabled /etc/nginx/sites-enabled

# Enviorement
ENV TZ=America/Sao_Paulo

RUN apk --no-cache add vim tzdata libmaxminddb libmaxminddb-dev \
&& rm -Rf /etc/nginx/conf.d/default.conf \
&& mkdir -p /etc/nginx/certs/mkcert \
&& chown 754 /etc/nginx/sites-available \
&& chown 754 /etc/nginx/sites-enabled \
&& ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf \
&& ln -s /etc/nginx/sites-available/blazegrids.conf /etc/nginx/sites-enabled/blazegrids.conf \
# CRON CONFIGURATION
&& mkdir /etc/periodic/22hour \
&& mkdir /var/log/crond \
&& chmod 755 /var/log/crond \
&& chmod 755 /etc/periodic/22hour \
&& echo "0      22       *       *       *       run-parts /etc/periodic/22hour" >> /var/spool/cron/crontabs/root \
# BAD BOT CONFIGURATION LOG
&& mkdir /var/log/badbotblocker \
&& chmod 755 /var/log/badbotblocker

ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./certs/ /etc/nginx/certs/mkcert
ADD ./update-ngxblocker.sh/ /etc/periodic/22hour/update-ngxblocker