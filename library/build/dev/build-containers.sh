#!/bin/bash
# =========================================================================
# =========================================================================
#
#	build-containers
#	  Local build containers.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-utilities
# @subpackage build-containers
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-utilities.
#
#   ewsdocker/ldc-utilities is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-utilities is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-utilities.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

cd ~/Development/ewslms/ldc-utilities

. ../ldc-utilities/scripts/usr/local/lib/lms/lmsconCli.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsDeclare.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsDisplay.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsUtilities.lib

. ../ldc-utilities/scripts/usr/local/lib/lms/local-build.lib

# =========================================================================

declare    cSingle=""
declare    cListing="0"
declare    cListFile=""

# =========================================================================

setSoftVers "0.1.0"

setNetwork "pkgnet"
setHost "http://alpine-nginx-pkgcache"

# =========================================================================
# =========================================================================

# =========================================================================
#
#   imageBody
#      Create the docker command body to build the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function imageBody()
{
	return 0
}

# =========================================================================
#
#   buildImage
#      build the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function buildImage()
{
	[[ -n ${cExtMod} ]] && cExtMod="-${cExtMod}"

	imageName
	containerName

	lmsDisplayTs "Building \"${cImage}\"."

	imageHeader
	imageBody
	imageFooter

	cOutput=$( ${cCommand} ) 
	cStatus=$?

    [[ ${cStatus} -eq 0 ]] ||
     {
     	[[ ${cListing} -eq 1 ]] &&
     	 {
			[[ -z ${cListFile} ]] &&  "${cOutput}" || echo "${cOutput}" >> ${cListFile}
		 }

    	lmsDisplayTs "Build of \"${cImage}\" failed."
 	    return 1
 	 }

	[[ ${cListing} -eq 1 ]] &&
	 {
	 	[[ -z ${cListFile} ]] &&  "${cOutput}" || echo "${cOutput}" >> ${cListFile}
	 }

	lmsDisplayTs "\"${cImage}\" was successfully built."
	
	return 0
}

# =========================================================================
#
#	Functions to perform a single action on a single version.
#
# =========================================================================

# =========================================================================
#
#   buildSelected
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function buildSelected()
{
	cDockerName="${cVersion}"
	cSoftName="${cVersion}"

	buildImage
    return $?
}

# =========================================================================
#
#   rmSelected
#      build the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function rmSelected()
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
#   rmiSelected
#      remove the selectedimage
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function rmiSelected()
{
	[[ -n ${cExtMod} ]] && cExtMod="-${cExtMod}"

	imageName
	containerName

	lmsDisplayTs "Removing \"${cImage}\"."

	cCommand="docker rmi ${cImage}"
	cOutput=$( ${cCommand} ) 
	cStatus=$?

	lmsDisplayTs "${cOutput}"
	return 0
}

# =========================================================================
#
#   processSelected
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processSelected()
{
	local -i selRslt=0

	case $cAction in

		"build")

			buildSelected
			selRslt=$?

			;;
			
		"rm")
			rmSelected
			selRslt=$?
			
			;;
		
		"rmi")
			rmiSelected
			selRslt=$?

			;;
		
		*)
			buildSelected
			selRslt=$?

			;;

	esac

    return ${selRslt}
}

# =========================================================================
#
#	Functions to perform a single action on all versions.
#
# =========================================================================

# =========================================================================
#
#   buildImages
#      build all the permutations of an image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function buildImages()
{
	for cVersion in "dutilities" "dgui"
	do
		buildSelected
       	[[ $? -eq 0 ]] || return 1
    done
    
    return 0
}

# =========================================================================
#
#   processAll
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processAll()
{
    return 1
}

# =========================================================================
# =========================================================================

clear

cCommand=""

cRepo="ewsdocker"
cName="ldc-utilities"

cVersionList="dbase utilities"
cVersExt="${cSoftVers}"
cExtMod=""

cAction="build"
cActionList="build rm rmi"

cSingle=""
cListing="0"
cListFile=""
cTimestamp=""

lmsCliParse

for key in ${cliKey[@]}
do
	case $key in 

	    "single")
	    	cSingle="${cliParam[$key]}"
			;;

		"listing")
			cListing="1"
			;;

		"file")
			cListing="1"
			cListFile="${cliParam[$key]}"
		 	lmsTimestamp 1 "cTimestamp"

		 	echo "${cTimestamp}" > ${cListFile}
			;;

		*)
			lmsDeclareStr "$key" "${cliParam[$key]}"
			;;

	esac
done

if [[ -z ${cSingle} ]]
then
    buildImages
    [[ $? -eq 0 ]] || exit $?
else
	buildSelected
	[[ $? -eq 0 ]] || exit $?
fi

[[ -n ${cListFile} ]] &&
 {
 	lmsTimestamp 1 "cTimestamp"
 	echo "${cTimestamp}" >> ${cListFile}
 }

exit 0
