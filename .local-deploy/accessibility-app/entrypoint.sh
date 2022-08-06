#!/bin/bash

set -e
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

chown -R www-data ${PERMANENT_FILE_STORAGE} ${APP_HOME}/storage

rm -rf ${PERMANENT_FILE_STORAGE}/../deploy.lock

if [[ "$APP_ENV" == "local" ]]
then 
  /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord-local.conf
else 
  /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
fi
