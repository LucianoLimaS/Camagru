FROM php:8.2-apache

# Install system dependencies and PHP extensions (including GD for image processing, which is required for Camagru)
RUN apt-get update && apt-get install -y \
    msmtp \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite and AllowOverride All
RUN a2enmod rewrite && \
    sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Configure msmtp (mail agent) to route to mailpit SMTP
RUN echo "account default" > /etc/msmtprc && \
    echo "host mailpit" >> /etc/msmtprc && \
    echo "port 1025" >> /etc/msmtprc && \
    echo "from camagru@localhost" >> /etc/msmtprc && \
    echo "auth off" >> /etc/msmtprc && \
    echo "tls off" >> /etc/msmtprc

# Configure PHP to use msmtp for the native mail() function
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/sendmail.ini

# Set working directory
WORKDIR /var/www/html
