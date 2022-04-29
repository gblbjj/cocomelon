FROM php:7.4-apache

RUN apt update -y 
RUN apt install vim -y
RUN apt install sudo -y
RUN apt install telnet -y
RUN apt install  netcat -y
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis \
&&  docker-php-ext-install mysqli \
&& docker-php-ext-enable mysqli \
&& docker-php-ext-install pdo pdo_mysql
COPY ./files/products.php /var/www/html/products.php
COPY ./files/index.php /var/www/html/index.php
RUN chmod 777 -R /var/www/html/*
EXPOSE 80
RUN sudo service apache2 restart


