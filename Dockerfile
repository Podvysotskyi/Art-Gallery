FROM php:8.5-cli as php

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions bcmath zip exif redis pdo_pgsql gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /src

WORKDIR /src
RUN composer install \
    && chown -R www-data:www-data /src

USER www-data

FROM node:24-alpine as node

COPY --from=php /src /src

WORKDIR /src

RUN npm install && npm run build

FROM php:8.5-fpm as base

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions redis pdo_pgsql gd

EXPOSE 8000

COPY --from=node /src /app

WORKDIR /app

RUN chown -R www-data:www-data /app \
    && chown -R www-data:www-data /var/www

USER www-data

ENTRYPOINT ["/app/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
