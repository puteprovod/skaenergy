FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
      apt-utils \
      libpq-dev \
      libpng-dev \
      libzip-dev \
      zip unzip \
      git \
      libfreetype6-dev \
      libwebp-dev \
      libjpeg62-turbo-dev \
      libpng-dev \
      libgmp-dev \
      libldap2-dev \
      netcat \
      libsqlite3-dev && \
      docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-webp-dir=/usr/include/  --with-jpeg-dir=/usr/include/ && \
      docker-php-ext-install gd zip gmp pcntl ldap sysvmsg exif && \
      docker-php-ext-install pdo_mysql && \
      docker-php-ext-install bcmath && \
      apt-get clean && \
      rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY ./_docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs

WORKDIR /var/www

