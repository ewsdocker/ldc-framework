#!/bin/bash
# =========================================================================
# =========================================================================
#
#	build-ddialog
#	  Local dialog.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-dialog
# @subpackage build-ddialog
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-dialog.
#
#   ewsdocker/ldc-dialog is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-dialog is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-dialog.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

cd ~/Development/ewsldc/ldc-dialog

. ../ldc-utilities/scripts/usr/local/lib/lms/lmsconCli.lib

. ../ldc-utilities/scripts/usr/local/lib/lms/lmsUtilities.lib
. ../ldc-utilities/scripts/usr/local/lib/lms/lmsDisplay.lib

. ./scripts/usr/local/lib/lms/local-build.lib

# =========================================================================

declare    cDesktop=""
declare    cTemplate=""
declare    cDlg=""
declare    cPackage=""

declare    cRequest="build"
declare    cSingle=""
declare    cListing="0"
declare    cListFile=""

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
	lmsDisplayTs "Removing \"${cContainer}\"."

	cCommand="docker rm ${cContainer}"
	cOutput=$( ${cCommand} ) 
	cStatus=$?

	[[ ${cListing} -eq 1 ]] &&
	 {
	 	[[ -z ${cListFile} ]] && lmsDisplay "${cOutput}" || echo "${cOutput}" >> ${cListFile}
	 }

	return 0
}

# =========================================================================
#
#   rmiImage
#      remove the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function rmiImage()
{
	lmsDisplayTs "Removing docker image \"${cImage}\"."

	cCommand="docker rmi ${cImage}"
	cOutput=$( ${cCommand} ) 
	cStatus=$?

	[[ ${cListing} -eq 1 ]] &&
	 {
	 	[[ -z ${cListFile} ]] && lmsDisplay "${cOutput}" || echo "${cOutput}" >> ${cListFile}
	 }

	return 0
}

# =========================================================================
#
#   fromParent
#      Create the docker from body to build the image from
#
#   Enter:
#      repo = ldc repository (e.g. - ewsdocker)
#      parent = ldc project name
#      vers = version of ldc project to use
#      ext = versExt (e.g. - "-0.1.0")
#      mod = versExt modifier, or empty
#
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function fromParent()
{
	local lrepo="${1}"
	local lparent="${2}"
	local lvers="${3}"
	local lext="${4}"
	local lmod="${5}"

	addToBuffer "--build-arg FROM_REPO=${lrepo} "
	addToBuffer "--build-arg FROM_PARENT=${lparent} "
	addToBuffer "--build-arg FROM_VERS=${lvers} "
	addToBuffer "--build-arg FROM_EXT=${lext} "
	addToBuffer "--build-arg FROM_EXT_MOD=${lmod} "
}

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
	fromParent "${cRepo}" "${fName}" "${fVersion}" "${fVersExt}" "${fExtMod}"

	addToBuffer "--build-arg BUILD_DESKTOP=${cDesktop} "
	addToBuffer "--build-arg BUILD_TEMPLATE=${cTemplate} "
	addToBuffer "--build-arg DLG_${cDlg}=1 "
	addToBuffer "--build-arg BUILD_PKG=${cPackage} "

	[[ -n ${cSourceHost} && -n ${cNetwork} ]] &&
	 {
		addToBuffer "--build-arg ${cSourceName}=${cSourceHost} "
		addToBuffer "--build-arg ${cSourceVName}=${cSourceVers} "
	 }
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
			[[ -z ${cListFile} ]] && lmsDisplay "${cOutput}" || echo "${cOutput}" >> ${cListFile}
		 }

    	lmsDisplayTs "Build of \"${cImage}\" failed."
 	    return 1
 	 }

	[[ ${cListing} -eq 1 ]] &&
	 {
	 	[[ -z ${cListFile} ]] && lmsDisplay "${cOutput}" || echo "${cOutput}" >> ${cListFile}
	 }

	lmsDisplayTs "\"${cImage}\" was successfully built."
	
	return 0
}

# =========================================================================
#
#   buildRequest
#      build the request
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function buildRequest()
{
	[[ "${cExtMod}" == " " ]] && cExtMod="" || cExtMod="-${cExtMod}"

	imageName
	containerName

	case ${cRequest} in
		
		"build")
			buildImage
			[[ $? -eq 0 ]] || return 1
			
			;;
			
		"run")
			return 2
			
			;;
			
		"rm")
			rmContainer
			[[ $? -eq 0 ]] || return 3
		
			;;
			
		"rmi")
			rmiImage
			[[ $? -eq 0 ]] || return 4
		
			;;
			
		*)
			return 5
			
			;;
			
	esac
	
	return 0
}

# =========================================================================
#
#   processExt
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# =========================================================================
function processExt()
{
   	lmsDisplay

	cSourceName=""
    cSourceHost=""
    cSourceVName=""
    cSourceVers=""

    case ${cExtMod} in

       	"dialog") 
       		fRepo="ewsdocker"
			fName="ldc-core"
			fVersion="dcore"
			fVersExt="-0.1.0"
			fExtMod=""

			cDesktop="Dialog"
			cTemplate="run"
			cDlg="DIAL"
			cPackage="cdialog_ComeOn_Dialog_v.1.3-20160828"
            	
			cSoftName="dialog"
			cSoftVers="1.3-20160828"
			cSoftDesktop="Dialog"

            buildRequest
        	[[ $? -eq 0 ]] || return 1

            ;;

        "whiptail") 
			fName="ldc-core"
			fVersion="dcore"
			fVersExt="-0.1.0"
			fExtMod=""

			cDesktop="Whiptail"
			cTemplate="run"
			cDlg="WHIP"
			cPackage="Whiptail_v.0.52.19"
            	
			cSoftName="whiptail"
			cSoftVers="0.52.19"
			cSoftDesktop="Whiptail"

			buildRequest
        	[[ $? -eq 0 ]] || return 1

            ;;

        "zenity") 
			fName="ldc-lang"
			fVersion="dgtk"
			fVersExt="-0.1.0"
			fExtMod="-gtk3"

			cDesktop="Zenity"
			cTemplate="gui"
			cDlg="ZENITY"
			cPackage="Zenity_v.3.22.0"

			cSoftName="zenity"
			cSoftVers="3.22.0"
			cSoftDesktop="Zenity"

            buildRequest
        	[[ $? -eq 0 ]] || return 1

            ;;

        "kaptain") 
            fName="ldc-lang"
			fVersion="dqt"
			fVersExt="-0.1.0"
			fExtMod="-qt4"

            cSourceName="KAPTAIN_HOST"
            cSourceHost="http://alpine-nginx-pkgcache"
            cSourceVName="KAPTAIN_VERS"
            cSourceVers="0.73-2"

			cSoftName="kaptain"
			cSoftVers="0.73-2"
			cSoftDesktop="Kaptain"

			cDesktop="Kaptain"
			cTemplate="gui"
			cDlg="KAPT"
			cPackage="${cDesktop}_v.${cSourceVers}"
            
            buildRequest
        	[[ $? -eq 0 ]] || return 1 
        	
            ;;

        *) 
           	return 1

            ;;

   	esac
       	
    return 0
}

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
    for cExtMod in "dialog" "whiptail" "zenity" "kaptain"
    do
    	processExt
    	[[ $? -eq 0 ]] || return 1
    done
}

# =========================================================================
# =========================================================================

clear

cDockerName="ddialog"

cSoftName="dialog"
cSoftVers="1.3-20160828"
cSoftDesktop="Dialog"

cNetwork="pkgnet"
cLocalHost="http://alpine-nginx-pkgcache"

cCommand=""
cRequest="build"

cRepo="ewsdocker"
cName="ldc-dialog"
cVersion="ddialog"
cVersExt="0.1.0"
cExtMod=""

cSingle=""
cListing="0"
cListFile=""
cTimestamp=""

lmsCliParse

for key in ${cliKey[@]}
do
	case $key in 

	    "build")
	    	cRequest="build"
			;;
				
	    "single")
	    	cSingle="single"
			;;
				
	    "request")
	    	cRequest="${cliParam[$key]}"
			;;
				
	    "rm")
	    	cRequest="rm"
			;;
				
	    "rmi")
	    	cRequest="rmi"
			;;
				
	    "run")
	    	cRequest="run"
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

if [[ ${cSingle} == "single" ]]
then
	processExt
	[[ $? -eq 0 ]] || exit $?
else
    buildImages
    [[ $? -eq 0 ]] || exit $?
fi

[[ -n ${cListFile} ]] &&
 {
 	lmsTimestamp 1 "cTimestamp"
 	echo "${cTimestamp}" >> ${cListFile}
 }

lmsDisplay

exit 0

