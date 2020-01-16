#!/bin/bash

apt-get -y update
apt-get -y upgrade

apt-get install -y \
        dpkg \
        libperl5.24 \
        libterm-readline-gnu-perl \
        perl \
        perl-base \
        perl-doc \
        perl-modules-5.24 \
        netbase \
        rename 

apt-get clean all

