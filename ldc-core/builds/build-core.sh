#!/bin/bash
# =========================================================================
# =========================================================================
#
#	build-core
#	  Local build core.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-core
# @subpackage build-core
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

cd ~/Development/ewsldc/ldc-core

# =========================================================================

bld_optLib=0
utilPath="../ldc-utilities"
[[ ${bld_optLib} -eq 1 ]] && libpath="./scripts/usr/local/lib/lms" || libpath="${utilpath}/scripts/usr/local/lib/lms"

# =========================================================================================
# =========================================================================================
#
#	Required Libraries
#
# =========================================================================================
# =========================================================================================

. ${libpath}/lmsBashVars.lib
. ${libpath}/lmsColorDef.lib
. ${libpath}/lmsconCli.lib
. ${libpath}/lmsDeclare.lib
. ${libpath}/lmsDisplay.lib
. ${libpath}/lmsDockerQuery.lib
. ${libpath}/lmsParseColumns.lib
. ${libpath}/lmsStr.lib

. ${libpath}/local-build.lib

. builds/build-body.sh

# =========================================================================

# =========================================================================
# =========================================================================
#
#	Utility Functions
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	initialize
#		initialize global variables
#
# =========================================================================
function initialize()
{
	cTrace=0

    # =====================================================================

	setSoftVers "0.1.0"

	setNetwork "pkgnet"
	setHost "http://alpine-nginx-pkgcache"

    # =====================================================================

	cCommand=""

	cRepo="ewsdocker"
	cName="ldc-core"

	cVersion=""
	cVersionList="dcore dgui dmpad"

	cVersExt="$cSoftVers"
	cVersExtList=""

	cExtMod=""
	cExtModList=""

	cAction="all"
	cActionList="stop rm rmi build all"

	cSingle=0
	cListing="1"
	cListFile=""
	cTimestamp=""

    # =====================================================================

	lmsCliParse

	for key in ${cliKey[@]}
	do
		case $key in 

	    	"single")
	    		cSingle="${cliParam[$key]}"

	    		lmsStrIsInteger $cSingle
	    		if [ $? -ne 0 ]
	    		then
	    			[[ "$cVersionList" =~ "$cSingle" ]] ||
	    			 {
	    		 		lmsDisplayTs "cSingle is unknown: ${cSingle}"
	    		 		return 1
	    			 }
	    			cVersion=$cSingle
	    			cSingle=1

	    		fi

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
	
	return 0
}

# =========================================================================
#
#   processListing
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processListing()
{
	[[ ${cListing} -eq 1 ]] &&
	 {
 		[[ -z ${cListFile} ]] && lmsDisplay "${cOutput}" || echo "${cOutput}" >> ${cListFile}
	 }

	[[ -n ${cListFile} ]] &&
	 {
 		lmsTimestamp 1 "cTimestamp"
 		echo "${cTimestamp}" >> ${cListFile}
	 }
}

# =========================================================================
# =========================================================================
#
#	Functions to perform a single action on a single version.
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#   buildSelected
#      build the selected image
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
	lmsDisplayTs "Building \"${cImage}\"."

	imageHeader
	imageBody
	imageFooter

    [[ ${cStatus} -eq 0 ]] || return ${cStatus} 

	[[ -z ${cListFile} ]] && ${cCommand} || cOutput=$( ${cCommand} ) 
	cStatus=$?

	return ${cStatus}
}

# =========================================================================
#
#   rmiSelected
#      remove the selected image
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
	lmsDisplayTs "Removing \"${cImage}\"."

	cCommand="docker rmi ${cImage}"

	[[ -z ${cListFile} ]] && ${cCommand} || cOutput=$( ${cCommand} )
	cStatus=$?

	return ${cStatus}
}

# =========================================================================
#
#   rmSelected
#      remove the container
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
	lmsDisplayTs "Removing \"${cContainer}\"."

	cCommand="docker rm ${cContainer}"
	[[ -z ${cListFile} ]] && ${cCommand} || cOutput=$( ${cCommand} )
	cStatus=$?

	return ${cStatus}
}

# =========================================================================
#
#   stopSelected
#      stop the selected container
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function stopSelected()
{
	lmsDisplayTs "Stopping \"${cImage}\"."

	cCommand="docker stop ${cImage}"
	[[ -z ${cListFile} ]] && ${cCommand} || cOutput=$( ${cCommand} )
	cStatus=$?

	return ${cStatus}
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
	cStatus=0

	cDockerName="${cVersion}"
	cSoftName="${cVersion}"

	imageName
	containerName

echo "container: $cContainer"
echo "imageName: $cImage"

	case ${cAction} in

		"build")

			buildSelected
			cStatus=$?

			;;
			
		"rm")
			rmSelected
			cStatus=0
			
			;;
		
		"rmi")
			rmiSelected
			cStatus=0

			;;
		
		"stop")
			stopSelected
			cStatus=0

			;;
		
		"all")
			buildSelected
			cStatus=$?

			;;

		*)
			cStatus=1

			;;

	esac

	[[ ${cListing} -eq 1 ]] &&
	 {
	 	[[ -z ${cListFile} ]] &&  lmsDisplay "${cOutput}" || echo "${cOutput}" >> ${cListFile}
	 }

    return ${cStatus}
}

# =========================================================================
# =========================================================================
#
#	Functions to perform a single action on all versions.
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#   processExtMods
#      process the ExtMod for all ExtMod's of an image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processExtMods()
{
	if [[ -n $cExtModList && -z $cExtMod ]]  # if cExtMod not specified, loop through all cExtMod's
	then
		for cExtMod in ${cExtModList}
		do
echo "processExtMods, cExtMod: $cExtMod"
			processSelected
   			[[ ${cStatus} -eq 0 ]] || break
   		done
	else                    # if cExtMod not specified, process only that cExtMod
echo "processExtMods, selected cExtMod: $cExtMod"
		processSelected
   	fi

   	return ${cStatus}
}

# =========================================================================
#
#   processVersExt
#      process all VersExt's of an image, if cVersExt is empty
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processVersExt()
{
	if [ -z ${cVersExt} ]   # if Version specified, process only that cVersion
	then					
		for cVersExt in ${cVersExtList}
		do
echo "processVersExt, cVersExt: $cVersExt"
			processExtMods
   			[[ ${cStatus} -eq 0 ]] || break
   		done
	else                    # if cVersion is not specified, process all cVersion's
echo "processVersExt, selected cVersExt: $cVersExt"
		processExtMods
   	fi

   	return ${cStatus}
}

# =========================================================================
#
#   processVersion
#      process all Version's of an image, if cVersion is empty
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processVersion()
{
	if [ -z ${cVersion} ]   # if Version specified, process only that cVersion
	then					
		for cVersion in ${cVersionList}
		do
echo "processVersion, cVersion: $cVersion"
			processVersExt
   			[[ ${cStatus} -eq 0 ]] || break
   		done
	else                    # if cVersion is not specified, process all cVersion's
echo "processVersion, selected cVersion: $cVersion"
		processVersExt
   	fi

   	return ${cStatus}
}

# =========================================================================
#
#   processSingle
#      process single image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processSingle()
{
echo "processSingle cAction: $cAction"
	case $cAction in

		stop|rm|rmi|build)
    		processVersion
    		[[ ${cAction} == "build" ]] || cStatus=0

			;;

    	all)
			for cAction in ${cActionList}
			do
				[[ ${cAction} == "all" ]] && continue
				processVersion
	    		[[ ${cAction} == "build" ]] || cStatus=0

				[[ ${cStatus} -eq 0 ]] || break
			done

			;;

		*)
			cStatus=1
			;;
    esac
    
    return ${cStatus}
}

# =========================================================================
#
#   process
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function process()
{
	for cAction in ${cActionList}
	do
echo "process action: $cAction"

		cVersion=""
		cExtMod=""

		[[ ${cAction} == "all" ]] && continue
echo "process version action: $cAction"
		processVersion
   		[[ ${cAction} == "build" ]] || cStatus=0

    	[[ ${cStatus} -eq 0 ]] || break
echo ""

	done

    return ${cStatus}
}

# =========================================================================
#
#	Script starts here
#
# =========================================================================

initialize

cStatus=0

echo "single: ${cSingle}"

if [[ ${cSingle} -eq 0 ]]
then
	process
else
	processSelected
fi

processListing

# =========================================================================

lmsDisplayTs "Exiting, result = ${cStatus}"

# =========================================================================

exit ${cStatus}
