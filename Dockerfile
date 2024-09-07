# Utiliser l'image officielle PHP 8.3 avec FPM
FROM php:8.3-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    libxml2-dev \
    libonig-dev \
    libxslt1-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl zip gd pdo pdo_mysql pdo_pgsql opcache bcmath mbstring exif pcntl xml xsl

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Ajouter des configurations personnalisées pour PHP
RUN echo "memory_limit=1024M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "upload_max_filesize=50M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "post_max_size=50M" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "max_execution_time=400" >> /usr/local/etc/php/conf.d/custom.ini

# Créer un utilisateur non-root pour exécuter Composer
RUN useradd -m -s /bin/bash appuser

# Copier le code source dans le conteneur
COPY . /var/www/html

# Changer le propriétaire des fichiers copiés
RUN chown -R appuser:appuser /var/www/html

# Passer à l'utilisateur non-root
USER appuser

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP en tant qu'utilisateur non-root
RUN composer install

# Revenir à l'utilisateur root pour le reste des configurations
USER root

# Définir les permissions appropriées
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Commande par défaut pour démarrer PHP-FPM
CMD ["php-fpm"]