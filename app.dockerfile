FROM php:8.3-fpm AS php

# Install dependencies and libraries
RUN apt-get update && apt-get install -y npm nodejs git curl libonig-dev libxml2-dev libpng-dev zip unzip
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
	
# Setup Composer
COPY --from=composer:2.2.25 /usr/bin/composer /usr/local/bin/composer

# Setup group and user.
# RUN groupadd -g 1000 web && useradd -m -u 1000 -g web webuser

WORKDIR /app

# Copy the project source
COPY . .
RUN mv .env.production .env

# Install composer
RUN composer install --prefer-dist

# Install npm and build asset
RUN npm install
RUN npm run build

EXPOSE 8000

CMD [ "./start.bash" ]
