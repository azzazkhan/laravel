FROM serversideup/php:8.3-fpm-nginx

# Provided by base image
ENV PHP_OPCACHE_ENABLE=1
ENV APP_BASE_DIR=/var/www
ENV COMPOSER_ALLOW_SUPERUSER=0
ENV NGINX_WEBROOT=/var/www/public
ENV PHP_MAX_EXECUTION_TIME=30

# Custom NGINX template variables
ENV NGINX_UPLOAD_LIMIT=10M
ENV NGINX_REQUEST_TIMEOUT=30
ENV HEALTHCHECK_TIMEOUT=5s

USER root

# Install and configure Node
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash -
RUN apt-get update && apt-get install -y vim nodejs iputils-ping
RUN npm i -g yarn

# Install Bun and Deno
RUN curl -fsSL https://bun.sh/install | bash
RUN curl -fsSL https://deno.land/install.sh | sh

# Install required PHP extensions
RUN install-php-extensions bcmath decimal exif ffi gd grpc intl opcache pcntl \
    pdo_mysql pdo_pgsql swoole

RUN apt-get autoremove && apt-get autoclean

USER www-data

WORKDIR /var/www

COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-interaction --no-autoloader --no-scripts

COPY --chown=www-data:www-data package.json yarn.lock ./
RUN yarn install --non-interactive

USER root

COPY --chown=www-data:www-data . .
RUN chmod -R 0755 bootstrap/cache storage

USER www-data

RUN composer dump-autoload
RUN yarn build
RUN touch database/database.sqlite

RUN php artisan schedule:clear-cache
RUN php artisan storage:link --force

USER root

# Remove existing NGINX config to prevent conflicts
RUN rm -rf /etc/nginx/nginx.conf.template \
    /etc/nginx/conf.d \
    /etc/nginx/server-opts.d \
    /etc/nginx/site-opts.d \
    /etc/nginx/snippets

COPY --chmod=755 docker/nginx/ /etc/nginx/
COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/

# Configure our custom entrypoint script and set proper permissions for our
# custom NGINX configurations
RUN docker-php-serversideup-s6-init
RUN docker-php-serversideup-set-file-permissions --owner www-data:www-data --service nginx

USER www-data
