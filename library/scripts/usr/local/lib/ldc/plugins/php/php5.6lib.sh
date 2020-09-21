
# ===================================================================================
# ===================================================================================
#
#	php5.6lib.sh
#       Php5.6 plugin script.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-framework
# @subpackage plugins/php
#
# ===================================================================================
#
#	Copyright © 2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-framework.
#
#   ewsdocker/ldc-framework is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-framework is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-framework.  If not, see <http://www.gnu.org/licenses/>.
#
# ===================================================================================
# ===================================================================================

# ===================================================================================
#
#	phpInstallRepository - Install the PHP Repository into the APT Repository.
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function phpInstallRepository()
{
    echo "*****************************************************"
    echo ""
    echo "   Installing Php from sury"
    echo ""
    echo "*****************************************************"

    wget https://packages.sury.org/php/apt.gpg -O /etc/apt/trusted.gpg.d/php.gpg
    [[ $? -eq 0 ]] || return $?

    echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list

    apt-get -y update 

    return 0
}

# ===================================================================================
#
#	phpInstall5.6 - The Repository is already install in APT,
#                   install the selected PHP version and support packages.
#
#   Enter:
#       updateRepository = non-empty string causes update prior to install.
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function phpInstall5.6()
{
	while (true)
	do
		pkgAddItemList "libgd3 libicu65 libpcre3 libmcrypt4 php-common php-pear"
    	[[ $? -eq 0 ]] || break

		pkgAddItemList "php5.6 php5.6-apcu php5.6-cgi php5.6-cli php5.6-curl"
    	[[ $? -eq 0 ]] || break

		pkgAddItemList "php5.6-fpm php5.6-gd php5.6-intl php5.6-json"
    	[[ $? -eq 0 ]] || break

    	pkgAddItemList "php5.6-mbstring php5.6-mcrypt php5.6-mysqlnd"
    	[[ $? -eq 0 ]] || break

    	pkgAddItemList "php5.6-opcache php5.6-phar php5.6-readline php5.6-xml"
    	[[ $? -eq 0 ]] || break

		break
	done

	return $?
}

# ===================================================================================
#
#	phpInstall - Install the specified PHP repository into the 
#                APT Package Repository and the Php version.
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function phpInstall()
{
	while (true)
	do
    	phpInstallRepository 
    	[[ $? -eq 0 ]] || break

    	phpInstall5.6 
    	[[ $? -eq 0 ]] || break

    	pkgExecute
    	[[ $? -eq 0 ]] || break

		break
	done

    return $?
}

# =========================================================================
#
#   installPhp5.6 - install Php 5.6.
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# =========================================================================
function installPhp5.6()
{
    phpInstall 
    [[ $? -eq 0 ]] || 
     {
        echo "installPhp5.6 Unable to install PHP repository ($?)"
        return 1
     }

    phpInstallComposer
    [[ $? -eq 0 ]] || 
     {
	    echo "installPhp5.6: phpInstallComposer unable to install Composer ($?)."
	    return 2
     }

    return 0
}

