FROM php:7.1.20-apache
RUN apt-get -y update --fix-missing
RUN apt-get upgrade -y

# Install libraries
RUN apt-get -y install --fix-missing apt-utils

# PHP Extensions
RUN apt-get -y install libmcrypt-dev
RUN docker-php-ext-install mcrypt

RUN apt-get -y install libsqlite3-dev libsqlite3-0 mysql-client
RUN docker-php-ext-install pdo_mysql 
RUN docker-php-ext-install mysqli