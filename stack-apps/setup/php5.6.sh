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
# =======================================================================

echo "*****************************************************"
echo ""
echo "   Installing Php5.6 from sury"
echo ""
echo "*****************************************************"

wget https://packages.sury.org/php/apt.gpg -O /etc/apt/trusted.gpg.d/php.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

apt-get -y update 

echo "*****************************************************"
echo ""
echo "   ldc-devstack dphp5.6"
echo ""
echo "*****************************************************"

addPkg "apt-get -y install"

# =======================================================================

addPkg "libgd3"
addPkg "libicu65"

addPkg "libpcre3"
addPkg "libmcrypt4"

addPkg "php-common"
addPkg "php-pear"

addPkg "php5.6"
addPkg "php5.6-apcu"
addPkg "php5.6-cgi"
addPkg "php5.6-cli"
addPkg "php5.6-curl"
addPkg "php5.6-fpm"
addPkg "php5.6-gd"
addPkg "php5.6-intl"
addPkg "php5.6-json"
addPkg "php5.6-mbstring"
addPkg "php5.6-mcrypt"
addPkg "php5.6-mysqlnd"
addPkg "php5.6-opcache"
addPkg "php5.6-phar"
addPkg "php5.6-readline"
addPkg "php5.6-xml"

# =======================================================================

echo ""
echo "$instList"
echo ""

$instList
[[ $? -eq 0 ]] || exit $?

apt-get clean all 

# =======================================================================

mkdir -p /opt/composer

lpwd=${PWD}
cd /opt/composer

curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer

cd $lpwd

