#!/bin/bash
# =========================================================================
# =========================================================================
#
#	rm-office
#	  Local rm all office containers.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-libre
# @subpackage rm-office
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-libre.
#
#   ewsdocker/ldc-libre is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-libre is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-libre.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

cd ~/Development/ewslms/ldc-libre

. ../ldc-utilities/scripts/usr/local/lib/lms/lmsUtilities.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsDisplay.lib

. ./scripts/usr/local/lib/lms/local-build.lib

# =========================================================================

cDockerName="ddialog"

cSoftName="dialog"
cSoftVers="0.1.0"

cNetwork="pkgnet"
cLocalHost="http://alpine-nginx-pkgcache"

# =========================================================================
#
#   rmContainer
#      remove the container
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function rmContainer()
{
	[[ "${cExtMod}" == " " ]] && cExtMod="" || cExtMod="-${cExtMod}"

	imageName
	containerName

	lmsDisplayTs "Removing \"${cContainer}\"."

	cCommand="docker rm ${cContainer}"
	cOutput=$( ${cCommand} ) 
	cStatus=$?

	return 0
}

# =========================================================================
#
#   rmiContainers
#      build all the permutations of a container
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function rmContainers()
{
    cRepo="ewsdocker"
    cName="ldc-dialog"
    cVersion="ddialog"
    cVersExt="0.1.0"

    for cExtMod in "dialog" "whiptail" "zenity" "kaptain"
    do
    	rmContainer
    done
}

# =========================================================================
# =========================================================================

cCommand=""
rmContainers

exit 0

