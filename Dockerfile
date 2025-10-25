FROM php:8.2-apache

# Instala la extensión mysqli y otras útiles (pdo_mysql, etc.)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilita mod_rewrite (por si usas frameworks PHP)
RUN a2enmod rewrite

# Copia los archivos de tu sitio al contenedor
COPY ./www /var/www/html/

EXPOSE 80
