FROM php:8.3-apache

# Instala a biblioteca necessária para a extensão intl
RUN apt-get update && apt-get install -y libicu-dev git unzip curl

# Instala o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Extensões comuns para CodeIgniter 4
RUN docker-php-ext-install mysqli pdo pdo_mysql intl pcntl

# Habilita mod_rewrite
RUN a2enmod rewrite

# Altera o DocumentRoot para /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf