#!/bin/bash

apt-get -y update
apt-get -y upgrade
apt-get install -y \
	libdouble-conversion1 \
	libevdev2 \
	libgraphite2-3 \
	libgudev-1.0-0 \
	libharfbuzz0b \
	libicu57 \
	libinput-bin \
	libinput10 \
	libmtdev1 \
	libpcre16-3 \
	libproxy1v5 \
	libqt5core5a \
	libqt5dbus5 \
	libqt5gui5 \
	libqt5network5 \
	libqt5svg5 \
	libwacom-common \
	libwacom2 \
	libxcb-icccm4 \
	libxcb-image0 \
	libxcb-keysyms1 \
	libxcb-randr0 \
	libxcb-render-util0 \
	libxcb-util0 \
	libxcb-xinerama0 \
	libxcb-xkb1 \
	libxkbcommon-x11-0 \
	mesa-utils \
	qt5-gtk-platformtheme \
	qt5-image-formats-plugins \
	qttranslations5-l10n \
	qtwayland5  
apt-get clean all
