FROM php:8.3-fpm AS php

# Move workdirectory
WORKDIR /var/www/html/Tinder_Demo

# Setup system.
RUN groupadd -g 1000 web && useradd -m -u 1000 -g web webuser

COPY --chown=webuser:web . /var/www/html/Tinder_Demo/

# Make webuser own the source code files.
RUN chown -R webuser:web /var/www/html/Tinder_Demo

# Setup Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Update apt-get.
RUN apt-get update && apt-get install -y

# Configure php extensions.
RUN apt-get install -y zip libzip-dev && docker-php-ext-install zip

# Switch to non-rooted user to install composer libraries (as recommendation)
USER webuser
RUN composer install --prefer-dist

USER root

EXPOSE 8000

CMD [ "php", "artisan", "serve", "--host", "0.0.0.0" ]
