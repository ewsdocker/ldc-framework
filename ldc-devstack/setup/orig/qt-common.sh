#!/bin/bash

apt-get -y update
apt-get -y upgrade
apt-get -y install \
           bsdmainutils \
           fontconfig \
           groff \
           groff-base \
           libjpeg62-turbo \
           libpipeline1 \
           qtchooser \
           libjbig0 \
           liblcms2-2 \
           libmng1 \
           libtiff5 
apt-get clean all


