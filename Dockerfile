# Use the official PHP 8.3 with Apache image
FROM php:8.3-apache

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies, PHP extensions, Composer, and clean up in ONE layer
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip \
    && apt-get remove -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && a2enmod rewrite

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy assets to public directory and install dependencies in ONE layer
RUN cp -r assets public/ \
    && composer install --no-dev --no-interaction --optimize-autoloader \
    && composer clear-cache \
    && rm -rf .git .gitignore .dockerignore tests docs *.md LICENSE \
    && chown -R www-data:www-data /var/www/html

# Configure Apache in a separate layer (for caching efficiency)
RUN rm /etc/apache2/sites-available/000-default.conf && \
    printf '<VirtualHost *:80>\n  ServerAdmin webmaster@localhost\n  DocumentRoot /var/www/html/public\n  <Directory /var/www/html/public>\n    Options Indexes FollowSymLinks\n    AllowOverride All\n    Require all granted\n  </Directory>\n  ErrorLog ${APACHE_LOG_DIR}/error.log\n  CustomLog ${APACHE_LOG_DIR}/access.log combined\n</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# The default command for the php:apache image is to start Apache
