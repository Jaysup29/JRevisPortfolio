#!/usr/bin/env bash
set -e

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
npm ci
npm run build

# Laravel setup
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure SQLite database exists
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Generate JWT secret if not set
php artisan jwt:secret --force 2>/dev/null || true
