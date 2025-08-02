FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Limpiar caché
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear usuario para la aplicación Laravel
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copiar código existente de la aplicación al contenedor
COPY . /var/www

# Cambiar al directorio de trabajo actual
WORKDIR /var/www

# Copiar permisos existentes de la aplicación
COPY --chown=www:www . /var/www

# Cambiar al usuario www
USER www

# Exponer puerto 9000 y iniciar servidor php-fpm
EXPOSE 9000
CMD ["php-fpm"]