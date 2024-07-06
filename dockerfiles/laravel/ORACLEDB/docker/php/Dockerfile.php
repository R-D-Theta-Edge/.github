# install php 8.2, composer and node and git
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update -y && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    wget \ 
    nano \
    libaio1 \
    && npm install -g npm@latest

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*


# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl


# instant client

ENV LD_LIBRARY_PATH=/opt/oracle/instantclient_19_23



# for x86_64 architecture, if use windows, uncomment this
 
# RUN wget https://download.oracle.com/otn_software/linux/instantclient/1919000/instantclient-basic-linux.x64-19.19.0.0.0dbru.el9.zip
# RUN wget https://download.oracle.com/otn_software/linux/instantclient/1919000/instantclient-sdk-linux.x64-19.19.0.0.0dbru.el9.zip
# RUN wget https://download.oracle.com/otn_software/linux/instantclient/1919000/instantclient-sqlplus-linux.x64-19.19.0.0.0dbru.el9.zip

# RUN unzip instantclient-basic-linux.x64-19.19.0.0.0dbru.el9.zip
# RUN unzip instantclient-sdk-linux.x64-19.19.0.0.0dbru.el9.zip
# RUN unzip instantclient-sqlplus-linux.x64-19.19.0.0.0dbru.el9.zip

#for macos and linux on arm, use this image
RUN mkdir /home/downloads && cd /home/downloads

RUN wget -q -O /home/downloads/instantclient-basic-linux.arm64-19.23.0.0.0dbru.zip 'https://download.oracle.com/otn_software/linux/instantclient/1923000/instantclient-basic-linux.arm64-19.23.0.0.0dbru.zip'
RUN wget -q -O /home/downloads/instantclient-sqlplus-linux.arm64-19.23.0.0.0dbru.zip 'https://download.oracle.com/otn_software/linux/instantclient/1923000/instantclient-sqlplus-linux.arm64-19.23.0.0.0dbru.zip'
RUN wget -q -O /home/downloads/instantclient-sdk-linux.arm64-19.23.0.0.0dbru.zip 'https://download.oracle.com/otn_software/linux/instantclient/1923000/instantclient-sdk-linux.arm64-19.23.0.0.0dbru.zip'

WORKDIR /home/downloads

RUN unzip -o instantclient-basic-linux.arm64-19.23.0.0.0dbru.zip
RUN unzip -o instantclient-sqlplus-linux.arm64-19.23.0.0.0dbru.zip
RUN unzip -o instantclient-sdk-linux.arm64-19.23.0.0.0dbru.zip


RUN mkdir -p /opt/oracle
RUN cp -r instantclient_19_23 /opt/oracle/

ENV ORACLE_HOME=/opt/oracle/instantclient_19_23 
ENV LD_LIBRARY_PATH="$ORACLE_HOME"
ENV PATH="$ORACLE_HOME:$PATH"

RUN docker-php-ext-configure oci8 --with-oci8=instantclient,/opt/oracle/instantclient_19_23
RUN docker-php-ext-install oci8
RUN echo /opt/oracle/instantclient_19_23 > /etc/ld.so.conf.d/oracle-instantclient.conf
RUN ldconfig



# # Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# #copy app to working directory
COPY . /var/www

# #cd into the app directory
WORKDIR /var/www

# create user to avoid running composer as root
ARG user=app
ARG uid=1000
ARG gid=1000

# Create the user
RUN groupadd -g $gid $user && useradd -u $uid -g $gid -m $user

#copy app to working directory
COPY . /var/www

#cd into the app directory
WORKDIR /var/www

# fix permission and ownership of the repo
RUN chown -R $user:$user /var/www

# switch user
USER $user

# run composer install
RUN composer install && php artisan key:generate

# # run npm run build
# RUN npm install && npm run build

# # Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]