FROM php:8.2-apache

# Instalar extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd

# Configurar o Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos da aplicação
COPY . .

# Instalar dependências do Composer
RUN composer install --no-interaction --no-plugins --no-scripts

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expor porta
EXPOSE 80

# Iniciar servidor Apache
CMD ["apache2-foreground"]