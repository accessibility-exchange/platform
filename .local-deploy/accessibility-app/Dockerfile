# syntax = edrevo/dockerfile-plus

INCLUDE+ ./.kube/app/Dockerfile

ARG USER_ID

RUN usermod -u $USER_ID www-data
RUN chown -R www-data:root /app $NVM_DIR
RUN chown www-data:root /var/www

COPY .local-deploy/accessibility-app/php.ini-development /usr/local/etc/php/conf.d/php.ini
COPY .local-deploy/accessibility-app/etc /etc
