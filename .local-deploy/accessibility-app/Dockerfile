# syntax = edrevo/dockerfile-plus

INCLUDE+ ./.kube/app/Dockerfile

# for development add in command for showing running processes
RUN apt install -y procps

COPY .local-deploy/accessibility-app/php.ini-development "$PHP_INI_DIR/conf.d/99-sail.ini"
# explicitly setup development php settings
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY .local-deploy/accessibility-app/etc /etc
