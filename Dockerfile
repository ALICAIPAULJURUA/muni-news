# Render runtime image for Muni News
FROM php:8.2-apache

# Apache: URL rewriting + response headers needed by Laravel
RUN a2enmod rewrite headers

# System deps + PHP extensions (SQLite, image processing, ZIP, PDF via Dompdf)
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip zip curl \
        libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
        libonig-dev libxml2-dev libzip-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd zip intl opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node 20 for the Vite build
ENV NODE_MAJOR=20
RUN curl -fsSL https://deb.nodesource.com/setup_${NODE_MAJOR}.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Install PHP + JS dependencies and compile production assets
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci \
    && npm run build

# Point Apache at Laravel's public folder
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Runtime filesystem scaffolding
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
    && chmod +x start.sh \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["bash", "/var/www/html/start.sh"]