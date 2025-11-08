FROM php:8.4-fpm

WORKDIR /var/www/html

# Copy php.ini
COPY ./php-fpm/php.ini /usr/local/etc/php/

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install PHP extensions installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/install-php-extensions
RUN chmod +x /usr/local/bin/install-php-extensions

# Install required PHP extensions
RUN install-php-extensions \
    mbstring \
    pdo_mysql \
    pdo_pgsql \
    zip \
    exif \
    pcntl \
    gd \
    intl \
    bcmath

# System dependencies + Xdebug + Git + Tools
RUN apt-get update && apt-get install -y \
    cron \
    supervisor \
    nano \
    git \
    zip \
    unzip \
    curl \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Memcached support
RUN apt-get install -y libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# Copy entrypoint script
COPY ./docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]