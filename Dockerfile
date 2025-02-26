# Použijeme oficiální PHP 8.1 CLI s Linuxem
FROM php:8.2-cli

# Nastavíme pracovní adresář v kontejneru
WORKDIR /var/www/html

# Nainstalujeme potřebné balíčky (zip, pdo_mysql apod.)
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev

RUN docker-php-ext-install pdo_mysql zip

# Zkopírujeme celý Laravel projekt do kontejneru
COPY . /var/www/html

# Nainstalujeme Composer (globálně) + composer install
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

RUN composer install

# Po spuštění kontejneru se rozjede zabudovaný server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
