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
# perl 5.28
#
addPkg "perl"
addPkg "perl-modules-5.28"
addPkg "libapt-pkg-perl"
addPkg "libperl5.28"
addPkg "perl-openssl-defaults"

addPkg "sgml-base"

addPkg "libencode-locale-perl"
addPkg "liberror-perl"

addPkg "libfile-listing-perl"
addPkg "libfile-mimeinfo-perl"
addPkg "libfile-basedir-perl"
addPkg "libfile-desktopentry-perl"

addPkg "libhtml-parser-perl"
addPkg "libhtml-tagset-perl"
addPkg "libhtml-tree-perl"
addPkg "libhttp-cookies-perl" 
addPkg "libhttp-date-perl"
addPkg "libhttp-message-perl"
addPkg "libhttp-negotiate-perl"

addPkg "libipc-system-simple-perl"
addPkg "libio-html-perl"
addPkg "libio-socket-ssl-perl"
addPkg "liblwp-mediatypes-perl"
addPkg "liblwp-protocol-https-perl"

addPkg "libnet-dbus-perl"
addPkg "libnet-http-perl"
addPkg "libnet-ssleay-perl"

addPkg "libpcre16-3" "16 bit Perl 5 Compatible Regular Expression Library runtime"

addPkg "libterm-readline-gnu-perl"
addPkg "libtext-iconv-perl" "converts between character sets in Perl"

addPkg "libtimedate-perl"
addPkg "liburi-perl"
addPkg "libwww-perl"
addPkg "libwww-robotrules-perl" 

addPkg "libxml-parser-perl"
addPkg "libxml-twig-perl"
addPkg "xml-core"

# =======================================================================

addPkg "libgdbm-compat4"
addPkg "libtry-tiny-perl"

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

