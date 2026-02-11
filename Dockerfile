# ----------------------------
# Étape 1 : Builder (composer + node si front)
# ----------------------------
FROM php:8.2-fpm AS builder

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le code et installer les dépendances PHP
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copier le reste de l'application
COPY . .

# Si tu as des assets front (Laravel Mix, Vite, etc.)
# RUN npm install && npm run build

# ----------------------------
# Étape 2 : Production image PHP-FPM
# ----------------------------
FROM php:8.2-fpm-alpine AS php-prod

# Installer les extensions nécessaires
RUN apk add --no-cache \
    libpng libjpeg freetype libzip \
    oniguruma \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copier l'application depuis le builder
WORKDIR /var/www/html
COPY --from=builder /app /var/www/html

# Définir les permissions (important pour Laravel)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
