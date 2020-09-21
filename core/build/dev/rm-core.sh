#!/bin/bash
# =========================================================================
# =========================================================================
#
#	rm-core
#	  Local rm all core containers.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-core
# @subpackage rm-core
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-core.
#
#   ewsdocker/ldc-core is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-core is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-core.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

cd ~/Development/ewslms/ldc-core

. ../ldc-utilities/scripts/usr/local/lib/lms/lmsUtilities.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsDeclare.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsDisplay.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/local-build.lib

# =========================================================================

cSoftVers="0.1.0"

cNetwork="pkgnet"
cLocalHost="http://alpine-nginx-pkgcache"

# =========================================================================
#
#   rmContainer
#      build the image
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
	[[ -n ${cExtMod} ]] && cExtMod="-${cExtMod}"

	imageName
	containerName

	cStatus=0
	[[ -e ${cContainer} ]] &&
	 {
		lmsDisplayTs "Removing \"${cContainer}\"."

		cCommand="docker rm ${cContainer}"
		cOutput=$( ${cCommand} ) 
		cStatus=$?

		lmsDisplayTs "${cOutput}"
	 }

	return 0
}

# =========================================================================
#
#   rmContainers
#      build all the permutations of an image
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
    cName="ldc-core"

    cVersExt="${cSoftVers}"
    cExtMod=""

    for cVersion in "dcore" "dgui"
    do
		cDockerName="${cVersion}"
		cSoftName="${cVersion}"

    	rmContainer
    done
}

# =========================================================================
# =========================================================================

cCommand=""
rmContainers

exit 0

