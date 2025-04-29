# syntax = edrevo/dockerfile-plus

INCLUDE+ ./.kube/app/Dockerfile

RUN apt install -y procps # for development add in command for showing running processes

COPY .local-deploy/accessibility-app/php.ini-development "$PHP_INI_DIR/conf.d/99-sail.ini"
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" # explicitly setup development php settings
COPY .local-deploy/accessibility-app/etc /etc
