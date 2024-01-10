FROM php:8.3-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/tabdmin/

# Set working directory
WORKDIR /var/tabdmin

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    nano \
    unzip \
    git \
    curl \
    software-properties-common \
    npm

# Install npm
RUN npm install npm@latest -g && \
    npm install n -g && \
    n latest

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# */

# Install extensions
RUN docker-php-ext-install pdo_mysql zip exif pcntl
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/tabdmin


# Copy existing application directory permissions
COPY --chown=www:www . /var/tabdmin

RUN composer install --optimize-autoloader --no-dev

RUN npm install
RUN npm run build
RUN rm -rf node_modules/

# Change current user to www
USER www


# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD php-fpm
