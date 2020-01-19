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
addPkg "perl perl-modules-5.24 libapt-pkg-perl libperl5.24"
addPkg "perl-openssl-defaults"

addPkg "sgml-base"

addPkg "libencode-locale-perl liberror-perl"

addPkg "libfile-listing-perl libfile-mimeinfo-perl"
addPkg "libfile-basedir-perl libfile-desktopentry-perl"

addPkg "libhtml-parser-perl"
addPkg "libhtml-tagset-perl libhtml-tree-perl libhttp-cookies-perl" 
addPkg "libhttp-date-perl libhttp-message-perl libhttp-negotiate-perl"

addPkg "libipc-system-simple-perl"
addPkg "libio-html-perl libio-socket-ssl-perl"
addPkg "liblwp-mediatypes-perl liblwp-protocol-https-perl"

addPkg "libnet-dbus-perl libnet-http-perl libnet-ssleay-perl"

addPkg "libpcre16-3" "16 bit Perl 5 Compatible Regular Expression Library runtime"

addPkg "libterm-readline-gnu-perl"
addPkg "libtext-iconv-perl" "converts between character sets in Perl"

addPkg "libtimedate-perl liburi-perl"
addPkg "libwww-perl libwww-robotrules-perl" 

addPkg "libxml-parser-perl libxml-twig-perl"
addPkg "xml-core"

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

