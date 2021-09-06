FROM composer as composer
COPY . /var/www/html/

FROM php:7.3-fpm
COPY --from=composer /var/www/html/ /var/www/html/
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt-get update && \
        apt-get install \
    libsodium-dev \
    zlib1g-dev \
    libzip-dev \
    nginx -y

RUN docker-php-ext-install \
    pdo pdo_mysql \
    sodium zip \
    && \
    docker-php-ext-enable \
    pdo \
    pdo_mysql \
    sodium \
    zip
RUN composer install
RUN chown www-data:www-data -R /var/www/html
COPY . /docker/nginx/nginx.conf /etc/nginx/sites-enabled/default
COPY . /docker/entrypoint.sh /etc/entrypoint.sh
RUN chmod +x /etc/entrypoint.sh
EXPOSE 80
CMD ["/etc/entrypoint.sh"]