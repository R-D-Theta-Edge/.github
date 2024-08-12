# install php 8.3, composer and node and git
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# create user to avoid running composer as root
ARG user=app
ARG uid=1000
ARG gid=1000

# Create the user
RUN groupadd -g $gid $user && useradd -u $uid -g $gid -m $user

#copy only backend directory to the container
COPY ./backend /var/www/[PROJECTNAME]

#cd into the app directory
WORKDIR /var/www/[PROJECTNAME]

# fix permission and ownership of the repo
RUN chown -R $user:$user /var/www

# switch user
USER $user


# run composer install and update
RUN composer install && composer update

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]