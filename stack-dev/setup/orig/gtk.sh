#!/bin/bash

echo "${GTKVER}"

case ${GTKVER} in
	"COMMON")
		echo ""
		echo "   GTK-COMMON"
		echo ""

        echo 'APT::Install-Recommends 0;' >> /etc/apt/apt.conf.d/01norecommends 
        echo 'APT::Install-Suggests 0;'   >> /etc/apt/apt.conf.d/02nosuggests 

        apt-get -y update
		apt-get install -y \
        	gtk-update-icon-cache \
        	libatk1.0-0 \
        	libatk1.0-data \
        	libavahi-client3 \
        	libavahi-common-data \
        	libavahi-common3 \
        	libcroco3 \
        	libcups2 \
        	libdatrie1 \
        	libgail-common \
        	libgraphite2-3 \
        	libgtk2.0-0 \
        	libgtk2.0-common \
        	libharfbuzz0b \
        	libicu57 \
        	libjbig0 \
        	libjpeg62-turbo \
        	libxml2 \
        	shared-mime-info \
        	xml-core

		;;

	"GTK2")
		echo ""
		echo "   GTK2"
		echo ""

        apt-get -y update
	    apt-get -y install \
	               gir1.2-gtk-2.0 \
	               gtk2.0-examples \
	               libgtk2.0-bin \
	               libgtk2.0-0-dbg \
	               libgtk2.0-dev \
	               libgtk2.0-doc

	    ;;

	"GTK3")
		echo ""
		echo "   GTK3"
		echo ""

        apt-get -y update
        apt-get -y install \
                   libgtk-3-0 \
                   libgtk-3-bin \
                   libgtk-3-common \
                   libgtkextra-3.0
        
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
