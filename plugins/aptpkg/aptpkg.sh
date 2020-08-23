
# ===================================================================================
# ===================================================================================
#
#	aptpkg.sh
#       Install the requested packages.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-framework
# @subpackage plugins/aptpkg
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

declare pkgList=" "

# ===================================================================================
#
#	pkgInit
#		Initialize the pkgList buffer to apt-get install command
#
#   Enter:
#		none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgInit()
{
    pkgList="apt-get -y install"
    return 0
}

# ===================================================================================
#
#	pkgAddItem
#		Add specified package to install list
#
#   Enter:
#		itemPkg = "package" name to add to the installation list
#		itemComment = comment... not used, but tolerated for documentation
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgAddItem()
{
	local itemPkg="${1}"
	local itemComment="${2}"

    printf -v pkgList "%s %s" "${pkgList}" "${itemPkg}"
    return 0
}

# ===================================================================================
#
#	pkgInstall
#		the pkgList has been built, now execute it
#
#   Enter:
#		none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgInstall()
{
	echo ""
	echo "$pkgList"
	echo ""

	$pkgList
	[[ $? -eq 0 ]] || return $?

    return 0
}

