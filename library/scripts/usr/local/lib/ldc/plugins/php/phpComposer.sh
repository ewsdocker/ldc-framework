
# ===================================================================================
# ===================================================================================
#
#	phpComposer.sh
#       Php Composer plugin script.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-framework
# @subpackage plugins/php5.6
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
#	phpInstallComposer - Install the PHP Repository into the APT Repository.
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function phpInstallComposer()
{
    local lpwd=${PWD}
    local lresult=0

    mkdir -p /opt/composer

    cd /opt/composer

    curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/bin/composer
    lresult=$?

    cd ${lpwd}
    return ${lresult}
}

