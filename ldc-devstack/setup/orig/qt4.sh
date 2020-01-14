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
           libtiff5 \
 \
	       libaudio2 \
	       libqt4-designer \
	       libqt4-dev \
	       libqt4-network \
	       libqt4-opengl-dev \
	       libqt4-qt3support \
	       libqt4-script \
	       libqt4-sql \
           libqt4-sql-mysql \
           libqt4-xml \
           libqtcore4 \
           libqtdbus4 \
           libqtgui4 \
           qt-at-spi \
           qt4-qtconfig \
           qtcore4-l10n \
           libdrm-amdgpu1 \
           libdrm-dev \
           libdrm-intel1 \
           libdrm-nouveau2 \
           libdrm-radeon1 \
           libgl1-mesa-dev \
           libglu1-mesa \
           libglu1-mesa-dev \
           libmariadbclient18 \
           libqt4-dbus \
           libqt4-declarative \
           libqt4-dev-bin \
           libqt4-help \
           libqt4-opengl \
           libqt4-scripttools \
           qt4-dev-tools 

apt-get clean all


