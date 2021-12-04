FROM php:8.0-apache
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN apt-get update && apt-get upgrade -y

# Copiar los archivos al VirtualHost
COPY ./Proyecto /var/www/html

RUN chgrp -R www-data /var/www
# Asignar el puerto
EXPOSE 80
CMD ["/usr/sbin/apache2ctl","-DFOREGROUND"]