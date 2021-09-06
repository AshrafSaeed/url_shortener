FROM composer as composer
COPY . /var/www/html/

FROM phpdockerio/php72-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-mysql php-xdebug php7.2-intl \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git flow
RUN apt-get update \
    && apt-get install git-flow

RUN composer install
RUN chown www-data:www-data -R /var/www/html
