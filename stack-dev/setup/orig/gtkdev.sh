#!/bin/bash

echo ""
echo "   GTKVER = ${GTKVER}"
echo ""

echo ""

case ${GTKVER} in
	"COMMON")
		echo ""
		echo "   GTK-COMMON"
		echo ""

        echo 'APT::Install-Recommends 0;' >> /etc/apt/apt.conf.d/01norecommends 
        echo 'APT::Install-Suggests 0;'   >> /etc/apt/apt.conf.d/02nosuggests 

        apt-get -y update
		apt-get install -y \
        	fontconfig \
        	gir1.2-atk-1.0 \
        	gir1.2-freedesktop \
        	gir1.2-pango-1.0 \
        	gnome-icon-theme \
        	gtk-update-icon-cache \
        	icu-devtools \
        	libatk1.0-0 \
        	libatk1.0-data \
        	libatk1.0-dev \
        	libavahi-client3 \
        	libavahi-common-data \
        	libavahi-common3 \
            libcairo-gobject2 \
            libcairo-script-interpreter2 \
            libcairo2-dev \
        	libcroco3 \
        	libcups2 \
        	libdatrie1 \
        	libexpat1-dev \
        	libfontconfig1-dev \
        	libfreetype6-dev \
        	libgail-common \
        	libgdk-pixbuf2.0-dev \
        	libgail18 \
        	libgdk-pixbuf2.0-0 \
        	libgdk-pixbuf2.0-common \
                   libgif7 \
        	libglib2.0-bin \
        	libglib2.0-data \
        	libglib2.0-dev \
        	libgraphite2-dev \
        	libgraphite2-3 \
        	libgtk2.0-0 \
        	libgtk2.0-common \
        	libharfbuzz-dev \
        	libharfbuzz-gobject0 \
        	libharfbuzz-icu0 \
        	libharfbuzz0b \
        	libice-dev \
        	libicu-dev \
        	libicu57 \
        	libjbig0 \
        	libjpeg62-turbo \
        	liblzo2-2 \
            libpango-1.0-0 \
        	libpango1.0-dev \
            libpangocairo-1.0-0 \
            libpangoft2-1.0-0 \
        	libpangoxft-1.0-0 \
        	libpcre16-3 \
        	libpcre3-dev \
        	libpcre32-3 \
        	libpcrecpp0v5 \
        	libpixman-1-dev \
        	libpng-dev \
            librsvg2-2 \
            librsvg2-common \
        	libsm-dev \
            libthai-data \
            libthai0 \
            libtiff5 \
            libxcb-render0-dev \
            libxcb-shm0-dev \
            libxcomposite-dev \
            libxcursor-dev \
            libxdamage-dev \
            libxext-dev \
            libxfixes-dev \
            libxft-dev \
            libxi-dev \
            libxinerama-dev \
            libxrandr-dev \
            libxrender-dev \
        	libxml2 \
        	sgml-base \
        	shared-mime-info \
        	x11proto-composite-dev \
        	x11proto-damage-dev \
        	x11proto-fixes-dev \
        	x11proto-randr-dev \
        	x11proto-render-dev \
        	x11proto-xext-dev \
        	x11proto-xinerama-dev \
        	xml-core \
        	zlib1g-dev

		;;

	"GTK2")
		echo ""
		echo "   GTK2"
		echo ""

        apt-get -y update
	    apt-get -y install gir1.2-gtk-2.0 \
						   gir1.2-pango-1.0 \
						   libgtk2.0-bin \
	                       libgtk2.0-dev \
        	               libgtk2.0-0-dbg \
						   libxml2-utils 

	    ;;

	"GTK3")
		echo ""
		echo "   GTK3"
		echo ""

        apt-get -y update
        apt-get -y install adwaita-icon-theme \
                           at-spi2-core \
                           dconf-gsettings-backend \
                           dconf-service \
                           gir1.2-atspi-2.0 \
                           gir1.2-gtk-3.0 \
                           glib-networking \
                           glib-networking-common \
                           glib-networking-services \
                           gsettings-desktop-schemas \
                           libatk-bridge2.0-0 \
                           libatk-bridge2.0-dev \
                           libatspi2.0-0 \
                           libatspi2.0-dev \
                           libcolord2 \
                           libdbus-1-dev \
                           libdconf1 \
                           libdrm-amdgpu1 \
                           libdrm-dev \
                           libdrm-intel1 \
                           libdrm-nouveau2 \
                           libdrm-radeon1 \
                           libegl1-mesa-dev \
                           libepoxy-dev \
                           libepoxy0 \
                           libgtk-3-0 \
                           libgtk-3-dev \
                           libgtk-3-bin \
                           libgtk-3-common \
                           libgtkextra-3.0 \
                           libgtkextra-dev \
                           libjson-glib-1.0-0 \
                           libjson-glib-1.0-common \
                           liblcms2-2 \
                           libpciaccess0 \
                           libproxy1v5 \
                           librest-0.7-0 \
                           libsoup-gnome2.4-1 \
                           libsoup2.4-1 \
                           libwayland-bin \
                           libwayland-dev \
                           libxcb-dri2-0-dev \
                           libxcb-dri3-dev \
                           libxcb-glx0-dev \
                           libxcb-present-dev \
                           libxcb-randr0 \
                           libxcb-randr0-dev \
                           libxcb-shape0-dev \
                           libxcb-sync-dev \
                           libxcb-xfixes0-dev \
                           libxkbcommon-dev \
                           libxshmfence-dev \
                           libxtst-dev \
                           libxxf86vm-dev \
                           wayland-protocols \
                           x11proto-dri2-dev \
                           x11proto-gl-dev \
                           x11proto-record-dev \
                           x11proto-xf86vidmode-dev 


	    ;;

	*)
		echo ""
		echo "Unknown stack: ${DSTACK}"
		echo ""

	    exit 1
	    ;;

esac

apt-get clean all

exit 0
