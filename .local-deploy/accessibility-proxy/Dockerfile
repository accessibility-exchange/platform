FROM nginx:1.23-alpine

RUN apk update
RUN apk add --no-cache openssl

ARG SSL_SUBJECT

RUN openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048

RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -subj "$SSL_SUBJECT" \
    -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt

COPY etc /etc

RUN mkdir -p /etc/nginx/sites-enabled
RUN cp /etc/nginx/sites-available/default_ssl.conf /etc/nginx/sites-enabled/default_ssl.conf
RUN cp /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf
