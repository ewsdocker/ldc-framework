#!/bin/bash

# ========================================================================================
#
#		ldc-core
#
# ========================================================================================

echo "***********************"
echo ""
echo "   ldc-core:dcore"
echo ""
echo "***********************"

echo 'APT::Install-Recommends 0;' >> /etc/apt/apt.conf.d/01norecommends
echo 'APT::Install-Suggests 0;' >> /etc/apt/apt.conf.d/02nosuggests
sed -i 's/^#\s*\(deb.*multiverse\)$/\1/g' /etc/apt/sources.list

apt-get -y update 
apt-get --allow-unauthenticated \
         -y install \
            acl \
            apt-transport-https \
            bash-completion \
            binutils \
            bzip2 \
            crda \
            cpp \
            cpp-6 \
            cron \
            curl \
            dbus \
            dbus-user-session \
            dconf-gsettings-backend \
            dconf-service \
            file \
            gcc \
            gcc-6 \
            gcc-6-locales \
            git \
            glib-networking \
            glib-networking-common \
            glib-networking-services \
            gnupg2 \
            less \
   	        libasan3 \
   	        libbsd0 \
            libcurl3-gnutls \
            libdconf1 \
            libglib2.0-0 \
            libgusb2 \
            libitm1 \
            libjson-glib-1.0-0 \
            libjson-glib-1.0-common \
            liblsan0 \
            libnet-dbus-perl \
            libmpc3 \
            libmpfr4 \
            libmpx2 \
            libpolkit-agent-1-0 \
            libpolkit-backend-1-0 \
            libpolkit-gobject-1-0 \
            libpthread-stubs0-dev \
            libquadmath0 \
            libsigsegv2 \
            libtool \
            libtsan0 \
            libubsan0 \
            libusb-1.0-0 \
            libxml2 \
            linux-libc-dev \
            locales \
            logrotate \
            lsb-release \
            make \
            m4 \
            nano \
            patch \
            perl \
            perl5.24 \
            policykit-1 \
            procps \
            psmisc \
            python \
            python-pthreading \
            python3 \
            sgml-base \
            shared-mime-info \
            software-properties-common \
            sudo \
            supervisor \
            syslog-ng \
            syslog-ng-core \
            udev \
            unzip \
            wget \
            xml-core \
            zip 

apt-get -y dist-upgrade 
 
apt-get clean all 

exit 0
