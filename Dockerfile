# Usar una imagen oficial de PHP con soporte para FPM y PostgreSQL
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_pgsql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html

# Solucionar problema de "safe.directory" para Git
RUN git config --global --add safe.directory /var/www/html

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Dar permisos al directorio de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 9000 para PHP-FPM
EXPOSE 9000

# Comando por defecto para iniciar PHP-FPM
CMD ["php-fpm"]
