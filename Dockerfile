FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions and gosu for user switching
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip && \
    apt-get update && \
    apt-get install -y gosu && \
    rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan commands
RUN useradd -G www-data,root -u 1000 -d /home/novarosamt novarosamt
RUN mkdir -p /home/novarosamt/.composer && \
    chown -R novarosamt:novarosamt /home/novarosamt

# Set working directory
WORKDIR /var/www/html

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# Ensure storage and bootstrap/cache directories exist
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache

# Configure PHP-FPM to run as novarosamt user
RUN sed -i "s/user = www-data/user = novarosamt/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/group = www-data/group = www-data/" /usr/local/etc/php-fpm.d/www.conf

# Keep running as root (entrypoint runs as root to fix permissions, then php-fpm handles user switching)

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

