#!/bin/bash

declare instList=" "

# =========================================================================
#
#	addPkg
#		add specified package to install list
#
#   Enter:
#		instPkg = "package" name to add to the installation list
#		instComment = comment... not used, but tolerated for documentation
#
# =========================================================================
function addPkg()
{
	local instPkg="${1}"
	local instComment="${2}"

    printf -v instList "%s %s" "${instList}" "${instPkg}"
    return 0
}

# =========================================================================
#
#	installList
#		the instList has been build, now execute it
#
#   Enter:
#		none
#
# =========================================================================
function installList()
{
	echo ""
	echo "$instList"
	echo ""

	$instList
	[[ $? -eq 0 ]] || return $?

    return 0
}

echo "*****************************************************"
echo ""
echo "   ldc-base:dbase"
echo ""
echo "*****************************************************"

apt-get -y update 

addPkg "apt-get -y install"

#
# base
#
addPkg "bash-completion"
addPkg "binutils"
addPkg "bsdmainutils"
addPkg "bzip2"

addPkg "cgmanager"

addPkg "dmsetup"

addPkg "file"

addPkg "iso-codes"
addPkg "iw"

addPkg "kmod"

addPkg "libbsd0"

addPkg "libcgmanager0"

addPkg "libedit2"

addPkg "libicu57"
addPkg "libinput-bin"
addPkg "libinput10"

addPkg "libkmod2" 

addPkg "libmagic-mgc"
addPkg "libmagic1"

addPkg "libncurses5"
addPkg "libnuma1"

addPkg "libpam-systemd"
addPkg "libpci3"
addPkg "libpciaccess0"
addPkg "libprocps6"

addPkg "nano" 

addPkg "pinentry-curses"
addPkg "procps"

addPkg "shared-mime-info"
addPkg "software-properties-common"
addPkg "systemd"
addPkg "systemd-shim"

#
# dbus
#
addPkg "dbus"
addPkg "dbus-user-session"

addPkg "libdbus-1-3"
addPkg "libdbus-glib-1-2"

addPkg "libdbusmenu-glib4"

#
# shml / xml
#
addPkg "apt-transport-https"
addPkg "libxml2"

#
# sanitizing
#
addPkg "libasan3"
addPkg "liblsan0"
addPkg "libsigsegv2"
addPkg "libubsan0"

# =======================================================================

#
# apt / apt-get
#
addPkg "libapt-inst2.0"

#
# dconf
#
addPkg "dconf-gsettings-backend"
addPkg "dconf-service"

addPkg "gsettings-desktop-schemas" 

addPkg "libdconf1"

#
# security
#
addPkg "libapparmor1"
addPkg "libcryptsetup4"
addPkg "libseccomp2"

#
# Policy Kit
#
addPkg "libpolkit-agent-1-0"
addPkg "libpolkit-backend-1-0"
addPkg "libpolkit-gobject-1-0"

addPkg "policykit-1"

#
# ssl
#
addPkg "ca-certificates"

addPkg "openssl"

#
# devices
#
addPkg "libdevmapper1.02.1"
addPkg "libgusb2"
addPkg "libieee1284-3"
addPkg "libusb-1.0-0"
addPkg "libmtdev1"
addPkg "libwacom-common"
addPkg "libwacom2"

#
# package-kit
#
addPkg "gir1.2-glib-2.0"
addPkg "gir1.2-packagekitglib-1.0"

addPkg "libgirepository-1.0-1"
addPkg "libpackagekit-glib2-18"

#
# 	Networking
#
addPkg "glib-networking"
addPkg "glib-networking-common"
addPkg "glib-networking-services"

addPkg "libip4tc0"
addPkg "libksba8" 

addPkg "libnl-3-200"
addPkg "libnl-genl-3-200"

addPkg "libproxy1v5"

addPkg "netbase"

#
#    Avahi is a fully LGPL framework for Multicast DNS Service Discovery
#
addPkg "libavahi-client3"
addPkg "libavahi-common-data"
addPkg "libavahi-common3"

#
# database
#
addPkg "libgdbm3"

addPkg "libjson-glib-1.0-0"
addPkg "libjson-glib-1.0-common"

#
# make
#
addPkg "m4"
addPkg "make"

addPkg "patch"

#
# C / C++
#
addPkg "libnih1"
addPkg "libnih-dbus1"

addPkg "cpp"
addPkg "cpp-6"
addPkg "gcc-6-locales"

addPkg "libassuan0"

addPkg "libdatrie1"

addPkg "libdouble-conversion1" "Convert IEEE floats to/from strings"

addPkg "libevdev2" "wrapper library for evdev (event driven) devices"

addPkg "libgomp1"
addPkg "libgudev-1.0-0" "GObject-based wrapper library for libudev"

addPkg "libisl15"

addPkg "libmpc3"
addPkg "libmpfr4"
addPkg "libmpx2"

addPkg "libquadmath0"

addPkg "libsecret-1-0"    "Library for storing and retrieving passwords/secrets."
addPkg "libsecret-common" "data files"

addPkg "libxslt1.1"

#
# python
#
addPkg "python-apt-common"  

addPkg "python3-apt"
addPkg "python3-dbus" 
addPkg "python3-gi"
addPkg "python3-pycurl"
addPkg "python3-software-properties" 

#
# Thread Management
#
addPkg "libnpth0"

installList

apt-get clean all 

# =======================================================================

mkdir -p /usr/local/bin
mkdir -p /usr/bin/lms
mkdir -p /usr/local/lib/lms/tests
mkdir -p /etc/lms

chmod +x /my_init
chmod +x /my_service

cp /usr/bin/lms/lms-setup.sh /etc/my_runonce/lms-setup.sh
chmod +x /etc/my_runonce/*.sh
chmod +x /etc/lms/*.sh

chmod +x /usr/local/bin/*.*
chmod +x /usr/local/bin/*

chmod +x /usr/bin/lms/*.*
chmod +x /usr/bin/lms/*

chmod +x /usr/local/lib/lms/tests/*.sh

# ========================================================================

/tmp/install_gosu.sh

# ========================================================================

exit $?

