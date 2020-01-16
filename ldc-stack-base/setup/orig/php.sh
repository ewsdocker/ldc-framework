#!/bin/bash

wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

apt-get -y update
apt-get install -y \
        libedit2 \
        libgd3 \
        libicu64 \
        libmcrypt4 \
        libxslt1.1 \
        php-common \
        php-pear \
        php5.6 \
        php5.6-apcu \
        php5.6-cgi \
        php5.6-cli \
        php5.6-curl \
        php5.6-fpm \
        php5.6-gd \
        php5.6-intl \
        php5.6-json \
        php5.6-mbstring \
        php5.6-mcrypt \
        php5.6-mysqlnd \
        php5.6-opcache \
        php5.6-phar \
        php5.6-readline \
        php5.6-xml
apt-get clean all

mkdir -p /opt/composer

lpwd=${PWD}
cd /opt/composer

curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

cd $lpwd

