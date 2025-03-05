FROM php:8.3-fpm AS php

# Move workdirectory
WORKDIR /var/www/html/Tinder_Demo

# Update apt-get.
RUN apt-get update && apt-get install -y git curl libonig-dev libxml2-dev libpng-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Setup Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Setup group and user.
RUN groupadd -g 1000 web && useradd -m -u 1000 -g web webuser

# Copy the project source
COPY --chown=webuser:web . /var/www/html/Tinder_Demo/

# Switch to non-rooted user to install composer libraries (as recommendation)
USER webuser
RUN composer install --prefer-dist

USER root

EXPOSE 8000

CMD [ "./start.bash" ]
