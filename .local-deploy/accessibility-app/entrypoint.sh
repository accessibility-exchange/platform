#!/bin/bash

set -x 

mkdir -p ${PERMANENT_FILE_STORAGE}

rsync -a ${APP_HOME}/storage/ ${PERMANENT_FILE_STORAGE}/
rm -rf ${APP_HOME}/storage

ln -s ${PERMANENT_FILE_STORAGE} ${APP_HOME}/storage

if [ ! -f ${PERMANENT_FILE_STORAGE}/../deploy.lock ]
then

  touch ${PERMANENT_FILE_STORAGE}/../deploy.lock

  php artisan migrate:fresh --force
  php artisan db:seed DevSeeder --force
  php artisan view:clear
  php artisan storage:link
  php artisan google-fonts:fetch
  php artisan route:cache
  php artisan config:cache
fi

rm -rf ${PERMANENT_FILE_STORAGE}/../deploy.lock

if [[ "${DOLLAR}ENABLE_SSL" == "true" ]]
then 
  openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
  openssl req \
    -x509 \
    -nodes \
    -days 365 \
    -newkey rsa:2048 \
    -subj "/C=CA/ST=Nova Scotia/L=Halifax/O=IRIS/OU=IT Department/CN=platform.test" \
    -keyout /etc/ssl/private/nginx-selfsigned.key \
    -out /etc/ssl/certs/nginx-selfsigned.crt 
  ln -s /etc/nginx/sites-available/default_ssl /etc/nginx/sites-enabled/default_ssl
fi 

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
