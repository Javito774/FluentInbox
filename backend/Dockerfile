# Usa la imagen oficial de PHP con Apache
FROM php:apache

# Instala extensiones necesarias para PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilita módulos de Apache necesarios
RUN a2enmod rewrite

# Exposición del puerto 80 para Apache
EXPOSE 80
