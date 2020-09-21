
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

# ===================================================================================
# ===================================================================================
#
#	pkgReset
#		Reset the options and set the pkgList buffer to empty.
#
#	pkgInstall
#		Initialize the pkgList buffer to "apt-get -y install" command
#
#	pkgUpdate
#		Initialize the pkgList buffer to "apt-get -y update" command
#
#	pkgAddItem $pkgItem $pkgComment=""
#		Add specified package ($pkgItem) to pkgList
#
#	pkgExecute $silent=0
#		the pkgList has been built, now execute it then reset the pkgList
#
# ===================================================================================
# ===================================================================================

# ===================================================================================
# ===================================================================================
#
#    2020-09-01   Initial development (0.1.0-b3)
#
# ===================================================================================
# ===================================================================================

declare    pkgList=""
declare -i pkgListValid=0

# ===================================================================================
#
#	pkgReset
#		Reset the options and set the pkgList buffer to empty.
#
#   Enter:
#		none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgReset()
{
    pkgList=""
    pkgListValid=0

    return 0
}

# ===================================================================================
#
#	pkgInstall
#		Initialize the pkgList buffer to "apt-get -y install" command
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
    pkgList="apt-get -y install"
    pkgListValid=1

    return 0
}

# ===================================================================================
#
#	pkgUpdate
#		Initialize the pkgList buffer to "apt-get -y update" command
#
#   Enter:
#		none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgUpdate()
{
    pkgList="apt-get -y update"
    pkgListValid=1

    return 0
}

# =========================================================================
#
#	pkgInstallArchive
#		download & install requested package
#
#   Enter:
#		instPkg = "package" name downloaded - what you are going to extract from
#		instUrl = server to download the pkg from
#		instDir = directory to extract to
#   Exit:
#       0 = no error
#       non-zero = error code
#
# =========================================================================
function pkgInstallArchive()
{
	local instPkg="${1}"
	local instUrl="${2}"
	local instDir="${3}"

    wget "${instUrl}" 
    [[ $? -eq 0 ]] || return $?

    tar -xvf "${instPkg}" -C "${instDir}" 
    [[ $? -eq 0 ]] || return $?

    rm "${instPkg}"

    return 0
}

# ===================================================================================
#
#	pkgAddItem
#		Add specified package to install list
#
#   Enter:
#		pkgItem = "package" name to add to the installation list
#		pkgComment = comment... optional - not used, but tolerated for documentation
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgAddItem()
{
	local pkgItem=${1:-""}
	local pkgComment=${2:-""}

	[[ -z "${pkgItem}" ]] && return 1

    [[ ${pkgListValid} -eq 0 ]] &&
     {
	    pkgInstall
        [[ $? -eq 0 ]] || return $?
     }

    printf -v pkgList "%s %s" "${pkgList}" "${pkgItem}"

    return 0
}

# ===================================================================================
#
#	pkgAddItem
#		Add specified package to install list
#
#   Enter:
#		pkgItemList = "package" name to add to the installation list
#		pkgComment = comment... optional - not used, but tolerated for documentation
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgAddItemList()
{
	local lpkgList=${1:-""}
	local lpkgListItem=""
	local lpkgListErrors=0

	[[ -z "${lpkgList}" ]] && return 1

	for lpkgListItem in $lpkgList
	do
		pkgAddItem ${lpkgListItem}
		[[ $? -eq 0 ]] || lpkgListErrors=$(( lpkgListErrors+1 ))
	done

    return $lpkgListErrors
}

# ===================================================================================
#
#	pkgExecute
#		the pkgList has been built, now execute it then reset the pkgList
#
#   Enter:
#		silent = 1 to supress output to console.
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function pkgExecute()
{
	local silent=${1:-""}

	[[ ${pkgListValid} -eq 0 ]] && return 1

    [[ ${silent} -eq 1 ]] ||
     {
	    echo ""
	    echo "$pkgList"
	    echo ""
	 }

	$pkgList
	[[ $? -eq 0 ]] || return $?

    pkgReset

    return 0
}

