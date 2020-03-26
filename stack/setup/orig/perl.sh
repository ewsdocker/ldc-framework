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

# =======================================================================

echo "*****************************************************"
echo ""
echo "   ldc-base:dbase-perl"
echo ""
echo "*****************************************************"

apt-get -y update 

addPkg "apt-get -y install"

#
# perl 5.24
#
addPkg "perl-openssl-defaults"
addPkg "liberror-perl libfile-listing-perl libhtml-parser-perl"
addPkg "libhtml-tagset-perl libhtml-tree-perl libhttp-cookies-perl" 
addPkg "libhttp-date-perl libhttp-message-perl libhttp-negotiate-perl"
addPkg "libio-html-perl libio-socket-ssl-perl"
addPkg "liblwp-mediatypes-perl liblwp-protocol-https-perl"
addPkg "libnet-dbus-perl libnet-http-perl libnet-ssleay-perl"
addPkg "libtimedate-perl liburi-perl"
addPkg "libwww-perl libwww-robotrules-perl" 
addPkg "libxml-parser-perl libxml-twig-perl"
addPkg "libencode-locale-perl"

# =======================================================================

echo ""
echo "$instList"
echo ""

$instList
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

exit $?

