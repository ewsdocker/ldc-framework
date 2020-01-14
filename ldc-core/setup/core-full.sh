#!/bin/bash

declare instList=" "

# =========================================================================
#
#	addPkg
#		add specified package to install list
#
#   Enter:
#		instPkg = "package" name to add to the installation list
#
# =========================================================================
function addPkg()
{
	local instPkg="${1}"

    printf -v instList "%s %s" "${instList}" "${instPkg}"
    return 0
}

# =========================================================================

echo "***********************"
echo ""
echo "   ldc-core:dcore"
echo ""
echo "***********************"

echo 'APT::Install-Recommends 0;' >> /etc/apt/apt.conf.d/01norecommends
echo 'APT::Install-Suggests 0;' >> /etc/apt/apt.conf.d/02nosuggests
sed -i 's/^#\s*\(deb.*multiverse\)$/\1/g' /etc/apt/sources.list

apt-get -y update 

# =======================================================================

addPkg "apt-get -y install"

# =======================================================================

#
# Applications
#
addPkg "nano " 

#
# base
#
addPkg "bash-completion bzip2 cgmanager cron curl"
addPkg "distro-info-data dmsetup file iso-codes less"
addPkg "lsb-release locales kmod mime-support psmisc procps"
addPkg "readline-common software-properties-common sudo supervisor"
addPkg "systemd systemd-shim unzip wget zip"

addPkg "libbsd0 libcgmanager0 libcurl3 libcurl3-gnutls" 
addPkg "libevtlog0 libexpat1 libffi6"
addPkg "libmagic-mgc libmagic1"
addPkg "libpam-systemd libpopt0 libprocps6 libreadline7"

#addPkg "autotools-dev binutils iw shared-mime-info"
#addPkg "libnuma1 libsnappy1v5"

#
# unicode
#
addPkg "libicu57 libunistring0"

#
# ncurses
#
addPkg "libncurses5 pinentry-curses"

#
# xml
#
addPkg "sgml-base xml-core libxml2"

#
# dbus
#
addPkg "dbus dbus-user-session libdbus-1-3 libdbus-glib-1-2"
addPkg "libnih-dbus1"

#
# sanitizing
#
addPkg "libasan3 libatomic1 liblsan0 libpsl5 libsigsegv2 libtsan0 libubsan0"

# =======================================================================

#
# apt / apt-get
#
addPkg "apt-transport-https libapt-inst2.0"
#addPkg "apt-rdepends"

#
# dconf
#
#addPkg "dconf-gsettings-backend dconf-service gsettings-desktop-schemas" 
#addPkg "libdconf1 ucf"

#
# syslog
#
addPkg "logrotate"
addPkg "syslog-ng syslog-ng-core syslog-ng-mod-journal" 
addPkg "syslog-ng-mod-json syslog-ng-mod-mongodb syslog-ng-mod-sql"

#
# security
#
#addPkg "acl"
addPkg "libapparmor1 libhogweed4"
addPkg "libcryptsetup4 libk5crypto3"
addPkg "libnghttp2-14 libkeyutils1 libkmod2"
addPkg "libsasl2-2 libsasl2-modules-db libseccomp2"
addPkg "libp11-kit0"

#
# Policy Kit
#
addPkg "policykit-1"
addPkg "libpolkit-agent-1-0 libpolkit-backend-1-0 libpolkit-gobject-1-0"

#
# ssl
#
addPkg "ca-certificates openssl libssl1.0.2 libssl1.1"

#
# pgp
#
addPkg "gnupg2"
addPkg "gnupg gnupg-agent libassuan0"

#
# devices
#
addPkg "libdevmapper1.02.1 libivykis0"
#addPkg "libgusb2 libusb-1.0-0 libieee1284-3"
#addPkg "udev"

#
# package-kit
#
addPkg "libpackagekit-glib2-18"
addPkg "gir1.2-glib-2.0 gir1.2-packagekitglib-1.0 libgirepository-1.0-1"

#
# networking
#
addPkg "libgnutls30 libidn2-0 libip4tc0 libnet1"
addPkg "libkrb5-3 libkrb5support0 libgssapi-krb5-2 libksba8" 
addPkg "librtmp1 libssh2-1 libtasn1-6 libwrap0"

#addPkg "crda glib-networking glib-networking-common"
#addPkg "glib-networking-services netbase wireless-regdb"
#addPkg "libasyncns0 libnl-3-200 libnl-genl-3-200 libproxy1v5"

#
# Network Multicast
#
#addPkg "libavahi-client3 libavahi-common-data libavahi-common3"

#
# database
#
addPkg "libdbi1 libjson-c3 libyajl2"
addPkg "libldap-2.4-2 libldap-common libsqlite3-0 libmongoc-1.0-0" 

#addPkg "libjson-glib-1.0-0 libjson-glib-1.0-common libtdb1 libgdbm3"

#
# make
#
addPkg "m4 make patch"

#
# C / C++
#
addPkg "libglib2.0-0 libnih1 libmpdec2 libc-l10n"

#addPkg "cpp cpp-6" 
#addPkg "gcc gcc-6 gcc-6-locales libgcc-6-dev"
#addPkg "libc-dev-bin libc6-dev libcc1-0 libgomp1"
#addPkg "linux-libc-dev libmpc3 libmpfr4 libmpx2"
#addPkg "libtool libltdl7 libquadmath0"

#
# Intel Cilk Plus (C / C++)
#
addPkg "libbson-1.0-0"

#addPkg "libcilkrts5 libslang2 liborc-0.4-0"

#
# perl 5.24
#
#addPkg "perl perl-modules-5.24 perl-openssl-defaults"
#addPkg "liberror-perl libfile-listing-perl libhtml-parser-perl"
#addPkg "libhtml-tagset-perl libhtml-tree-perl libhttp-cookies-perl" 
#addPkg "libhttp-date-perl libhttp-message-perl libhttp-negotiate-perl"
#addPkg "libio-html-perl libio-socket-ssl-perl"
#addPkg "liblwp-mediatypes-perl liblwp-protocol-https-perl"
#addPkg "libnet-dbus-perl libnet-http-perl libnet-ssleay-perl"
#addPkg "libperl5.24 libtimedate-perl liburi-perl"
#addPkg "libwww-perl libwww-robotrules-perl" 
#addPkg "libxml-parser-perl libxml-twig-perl"
#addPkg "libencode-locale-perl"

#
# python
#
addPkg "dh-python psmisc"
addPkg "python python-apt-common python-meld3"  
addPkg "libpython-stdlib python-minimal python-pkg-resources" 
addPkg "libpython2.7-stdlib python2.7 python2.7-minimal libpython2.7-minimal"  
addPkg "libpython3-stdlib python3 python3-minimal python3-apt python3-dbus" 
addPkg "python3-gi python3-pycurl python3-software-properties" 
addPkg "python3.5 python3.5-minimal libpython3.5-stdlib libpython3.5-minimal"  

#
# Thread Management
#
addPkg "libnpth0"
#addPkg "libitm1 libpthread-stubs0-dev python-pthreading"

# =======================================================================

#
# graphics
#
addPkg "libgmp10"
#addPkg "libisl15"

# =======================================================================

$instList

# =======================================================================

apt-get -y dist-upgrade 
 
apt-get clean all 

exit 0
