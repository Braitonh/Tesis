FROM php:8.2-fpm

# Paquetes del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    nodejs npm \
 && rm -rf /var/lib/apt/lists/*

# Extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar extensión Redis
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario/grupo app (uid/gid 1000)
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Ajustar PHP-FPM pool para que use 'www' en lugar de 'www-data'
RUN sed -ri 's/^user = www-data/user = www/; s/^group = www-data/group = www/' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www

# Copiar código una sola vez con ownership correcto
COPY --chown=www:www . /var/www

# Preparar carpetas que Laravel necesita y permisos
RUN mkdir -p storage/logs storage/framework/{cache,data,sessions,testing,views} bootstrap/cache \
 && touch storage/logs/laravel.log \
 && chown -R www:www storage bootstrap/cache \
 && find storage -type d -exec chmod 775 {} \; \
 && find storage -type f -exec chmod 664 {} \; \
 && chmod -R 775 bootstrap/cache

USER www

EXPOSE 9000
CMD ["php-fpm"]
