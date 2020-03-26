#!/bin/bash

#
#		ldc-langdev
#

apt-get -y update
apt-get install -y \
        build-essential \
        dpkg-dev \
        ed \
        libasan3-dbg \
        		   libc-dev-bin \
        libc-dbg \
        		   libc6-dev \
        libcilkrts5-dbg \
        libdpkg-perl \
        libgcc1-dbg \
        libgcc-6-dev \
        	libgdk-pixbuf2.0-dev \
        libitm1-dbg \
        liblsan0-dbg \
        libmpx2-dbg \
        libquadmath0-dbg \
        libstdc++6-6-dbg \
        libstdc++-6-dev \
        libubsan0-dbg \
        manpages-dev \
        patch \
        pkg-config \
        python-setuptools \
        xz-utils 

exit 0

