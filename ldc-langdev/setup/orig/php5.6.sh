#!/bin/bash

apt-get -y update
apt-get -y upgrade
apt-get install -y \
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
        php5.6-phar \
        php5.6-xml
apt-get clean all
