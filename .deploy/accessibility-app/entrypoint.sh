#!/bin/sh

set -e

mkdir -p $FILES_PATH

rsync -a /app/storage/ $FILES_PATH/
rm -rf /app/storage

ln -s $FILES_PATH /app/storage
chown www-data:root /opt/data/storage/ -R

if [ ! -f $FILES_PATH/../deploy.lock ]
then

  touch $FILES_PATH/../deploy.lock

  php artisan deploy:initial
  
fi

rm -rf $FILES_PATH/../deploy.lock

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
