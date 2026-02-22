FROM php:8.2-apache

# Copy all files to the web root
COPY . /var/www/html/

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80
