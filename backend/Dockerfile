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
    default-mysql-client \
    && docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar e configurar OPcache para melhor performance
RUN docker-php-ext-install opcache
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Configurar PHP para melhor performance
COPY php.ini /usr/local/etc/php/php.ini

# Configurar o Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers expires deflate

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar apenas composer files primeiro (para cache de layers)
COPY composer.json composer.lock ./

# Instalar dependências do Composer (usar cache)
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader

# Copiar resto da aplicação
COPY . .

# Otimizar autoloader do Composer
RUN composer dump-autoload --optimize --no-dev

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Cache de configuração do Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Expor porta
EXPOSE 80

# Copia o entrypoint e dá permissão
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]