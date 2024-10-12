FROM azzazkhan/laravel:latest

WORKDIR /var/www

COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-interaction --no-autoloader --no-scripts

COPY --chown=www-data:www-data package.json yarn.lock ./
RUN yarn install --non-interactive

COPY --chown=www-data:www-data . .
RUN chmod -R 0755 bootstrap/cache storage

RUN composer dump-autoload
RUN yarn build
RUN touch database/database.sqlite

RUN php artisan package:discover
RUN php artisan schedule:clear-cache
RUN php artisan storage:link --force
RUN php artisan optimize:clear

USER root

# COPY --chmod=755 docker/nginx/ /etc/nginx/
COPY --chmod=755 docker/entrypoint.d/ /etc/entrypoint.d/

# Configure our custom entrypoint script and set proper permissions for our
# custom NGINX configurations
# RUN docker-php-serversideup-s6-init
# RUN docker-php-serversideup-set-file-permissions --owner www-data:www-data --service nginx

USER www-data
