# Use PHP with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy package files for npm
COPY package*.json ./

# Install frontend dependencies (including dev dependencies for build)
RUN npm ci

# Copy configuration files needed for Vite build
COPY vite.config.js ./
COPY tailwind.config.js ./
COPY postcss.config.js ./

# Copy resources folder for Vite build
COPY resources ./resources

# Copy public directory (needed for Vite output)
COPY public ./public

# Debug: Check what we have before build
RUN echo "========== BEFORE BUILD ==========" && \
    echo "Current directory:" && pwd && \
    echo "Files in root:" && ls -la && \
    echo "Public directory:" && ls -la public/ && \
    echo "Node version:" && node --version && \
    echo "NPM version:" && npm --version && \
    echo "Package.json scripts:" && cat package.json | grep -A 10 "scripts" && \
    echo "================================="

# Build frontend assets with verbose output
RUN echo "========== RUNNING BUILD ==========" && \
    npm run build 2>&1 | tee build.log && \
    BUILD_EXIT_CODE=${PIPESTATUS[0]} && \
    echo "Build exit code: $BUILD_EXIT_CODE" && \
    echo "=================================" && \
    echo "========== AFTER BUILD ==========" && \
    echo "Public directory:" && ls -la public/ && \
    echo "Public/build directory:" && ls -la public/build/ || echo "public/build does not exist!" && \
    echo "Manifest check:" && \
    (test -f public/build/manifest.json && echo "✅ manifest.json exists" && cat public/build/manifest.json || echo "❌ manifest.json NOT found") && \
    echo "Build log:" && cat build.log && \
    echo "=================================" && \
    test -f public/build/manifest.json || (echo "❌ FATAL: manifest.json not found after build" && exit 1)

# Now copy the rest of the application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions for Laravel
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/public/build \
    && chmod -R 775 \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Configure Apache to serve Laravel from /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/c\<Directory /var/www/html/public>\n\tOptions Indexes FollowSymLinks\n\tAllowOverride All\n\tRequire all granted\n</Directory>' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]