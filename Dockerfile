FROM php:8.5-cli as php

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions bcmath zip exif

WORKDIR /src

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install

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

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
