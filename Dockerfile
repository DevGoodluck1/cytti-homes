FROM php:8.2-apache

# Set Apache document root explicitly
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Copy all files to the web root
COPY . /var/www/html/

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure Apache to use the correct document root
RUN sed -i 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|<Directory "/var/www/html">|<Directory "${APACHE_DOCUMENT_ROOT}">|g' /etc/apache2/apache2.conf

# Set proper ownership for the web root
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
