#!/bin/bash

wget ${NJS_URL}; chmod +x ${NJS_NAME}; ./${NJS_NAME}; rm ${NJS_NAME}

apt-get -y update
apt-get -y install \
           nodejs \
           npm
apt-get clean all

