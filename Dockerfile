FROM php:8.2-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo mbstring bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www

# Copier le projet
COPY . .

# Installer dépendances Laravel
RUN composer install

# Droits
RUN chown -R www-data:www-data storage bootstrap/cache

# Port Laravel
EXPOSE 8000

# Lancer Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
