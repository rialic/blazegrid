FROM nginx:stable-alpine

RUN mkdir -p /etc/nginx/certs/mkcert

ADD ./default.conf /etc/nginx/conf.d/default.conf
ADD ./nginx.conf /etc/nginx/nginx.conf
ADD ./certs/ /etc/nginx/certs/mkcert