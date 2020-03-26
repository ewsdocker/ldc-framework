#!/bin/bash
# =========================================================================================
#
#  selectTemplate
#
#      gui application to select and copy a ckaptain template.
#
# =========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-framework
# @subpackage ldc-ck-library
# @name selectTemplate
#
# =========================================================================
#
#	Copyright © 2017-2020. EarthWalk Software
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
#   along with ewsdocker/ldc-framework.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================================
# =========================================================================================

# =========================================================================================
# =========================================================================================
#
#    Get libraries
#
# =========================================================================================
# =========================================================================================

. /usr/local/lib/lms/lmsConIn.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsErrorMsg.lib
. /usr/local/lib/lms/lmsStr.lib
. /usr/local/lib/lms/lmsUtilities.lib

# ===========================================================================

. /opt/ckaptain-lib/etc/vars/selectTemplate.vars
. /opt/ckaptain-lib/etc/errors/selectTemplate.errors

# ===========================================================================

. /opt/ckaptain-lib/lib/cklFileUtils.lib
. /opt/ckaptain-lib/lib/cklVars.lib
. /opt/ckaptain-lib/lib/cklWidget.lib

# ===========================================================================

. /opt/ckaptain-lib/share/ckw/dialogs/errorMessage.dialog
. /opt/ckaptain-lib/share/ckw/dialogs/messageBox.dialog
. /opt/ckaptain-lib/share/ckw/dialogs/selectSrcTemplate.dialog
. /opt/ckaptain-lib/share/ckw/dialogs/selectDestTemplate.dialog

# =========================================================================================
# =========================================================================================
#
#   Functions
#
# =========================================================================================
# =========================================================================================

# =========================================================================================
#
#	installTemplateErrors - install errorNames and errorText into lmsErrorMsg.lib
#
#	Parameters:
#       none
#
#   Results:
#       0 = no error
#
# =========================================================================================
function installTemplateErrors()
{
    for error in ${!selTemplateErrors[*]}
    do
        lmsErrorAdd "$error" "${selTemplateErrors[$error]}"
    done

    return 0
}

# ===========================================================================
#
#	selectTemplate - select the requested template
#
#	Parameters:
#       widgetType    = "source" (default) or "dest"
#		widgetName    = location to store the template name
#		widgetFolder  = location to store the processed command folder name
#
#   Results:
#       0 = no error
#       non-zero = error code
#
# ===========================================================================
function selectTemplate()
{
	local widgetType=${1:-"source"}
    local widgetName="${2}"
    local widgetFolder="${3}"

	ecName="none"

	while [[ true ]]
	do
        [[ -z "${widgetName}" || -z "${widgetFolder}" ]] && ecName="required"

        [[ "${ecName}" == "none" ]] || break

        # ===================================================================

	    local oPwd=$PWD
	    cd "${ck}"

        case $widgetType in

    	    "source")
    	        selsrcDir="${ck}"
    	        selsrcPositive=" Continue"
    	        selsrcNegative="Cancel"
    	    
                selectSrcTemplate "Template" "Source Folder"
	            [[ $? -eq 0 ]] || ecName="selsrc"

    	        ;;

    	    "dest")
    	        seldestDir="${ck}"
    	        seldestPositive=" Continue"
    	        seldestNegative="Cancel"
    	        seldestFolder=""

                selectDestTemplate "Template" "Destination Folder"
	            [[ $? -eq 0 ]] || ecName="seldest"

    	        ;;

            *)
	            ecName="widget-type"

    	        ;;

        esac

	    cd $oPwd

        [[ "${ecName}" == "none" ]] || break

        # ===================================================================

        [[ -z "${widgetResult}" ]] && ecName="widget-result"

        [[ "${ecName}" == "none" ]] || break

        # ===================================================================

	    cliParam=()
	    cliKey=()

	    lmsConInSplit "${widgetResult}"
        [[ $? -eq 0 ]] || ecName="parse-req"

        [[ "${ecName}" == "none" ]] || break

        # ===================================================================

        local fileType=${cliKey[0]}
        local filePath=${cliParam[$fileType]}

        [[ -z "${filePath}" ]] && ecName="required-path"

        [[ "${fileType}" == "new" ]] && ckTemplate=1 || ckTemplate=0

        fileNameFromPath "${filePath}" "${widgetName}" "${widgetFolder}" "${ck}" "/"
        [[ $? -eq 0 ]] || ecName="filename-path"

        break

    done

    return 0
}

# ===========================================================================
#
#    getSourceTemplate - prompt for source template folder name/location
#
#      parameters
#        none
#
#      returns
#        0 = no error
#        non-zero = error code
#
# ===========================================================================
function getSourceTemplate()
{
	ecName="none"

    while [[ true ]]
    do
    	ckResult=0

	    selsrcTitle="Select Source Template Folder"
        selsrcFolder="Source Folder Name: "

	    selectTemplate "source" "sourcePath" "sourceName"
	    [[ $? -eq 0 ]] || 
	     {
	     	[[ "${ecName}" == "none" ]] || ecName="seltemplate"
	     }

	    [[ "${ecName}" == "none" ]] || break

        # ===================================================================

	    sourceTemplate="${sourcePath}/${sourceName}"

	    selSkip=0

	    [[ "${sourceTemplate}" == "${ck}" ]] &&
	     {
	        msgboxPositive="{$cklicons/Tango/32x32/actions/go-previous.png}"
	        msgboxNegative="{$cklicons/Tango/32x32/actions/process-stop.png}"
            msgboxIcon="$cklicons/warningyellow.png"

	 	    messageBox "Invalid source: ${sourceTemplate}" "Invalid source."
	 	    [[ $? -eq 0 ]] || ecName="invalid-source"

	        [[ "${ecName}" == "none" ]] || break

	 	    selSkip=1
	     }

        # ===================================================================

	    [[ ${selSkip} -eq 0 ]] &&
	     {
	        fileExists "${sourceTemplate}"
	        [[ $? -eq 0 ]] && 
	         {
                msgboxPositive="{$cklicons/yes.png}"
                msgboxNegative="{$cklicons/no.png}"
                msgboxIcon="$cklicons/question3.png"

                messageBox "Source = ${sourceTemplate}" "Verify source selection."
                [[ $? -eq 0 ]] || ecName="source-verify"

	            [[ "${ecName}" == "none" ]] && break
	         }

            # ===============================================================

            msgboxPositive="{$cklicons/Tango/32x32/actions/go-previous.png}"
	        msgboxNegative="{$cklicons/Tango/32x32/actions/process-stop.png}"
            msgboxIcon="$cklicons/question3.png"

            messageBox "Source folder ${sourceTemplate} does not exist." "Retry."
            [[ $? -eq 0 ]] || ecName="folder-exists"

	        [[ "${ecName}" == "none" ]] || break
         }

    done

    [[ "${ecName}" == "none" ]] || return 1

    return 0
}

# ===========================================================================
#
#    getDestinationTemplate - prompt for destination template folder
#
#      parameters
#        none
#
#      returns
#        0 = no error
#        non-zero = error code
#
# ===========================================================================
function getDestinationTemplate()
{
	ecName="none"

    while [[ true ]]
    do
	    seldestDir=${ck}

	    selectTemplate "dest" "destPath" "destName"
        [[ $? -eq 0 ]] || 
	     {
	     	[[ "${ecName}" == "none" ]] || ecName="seltemplate"
	     }

        [[ "${ecName}" == "none" ]] || break

        # ===================================================================

        destTemplate="${destPath}"
        [[ -z "${destName}" ]] || destTemplate="${destTemplate}/${destName}"

	    selSkip=0

	    [[ -z "${destTemplate}" || "${destTemplate}" == "${ck}" || "${destTemplate}" == "${sourceTemplate}" ]] &&
	     {
            msgboxPositive="{$cklicons/Tango/32x32/actions/go-previous.png}"
	        msgboxNegative="{$cklicons/Tango/32x32/actions/process-stop.png}"
            msgboxIcon="$cklicons/warningred.png"
            
	 	    messageBox "${destTemplate}" "Invalid destination."
	 	    [[ $? -eq 0 ]] || ecName="invalid-dest"

	        [[ "${ecName}" == "none" ]] || break
	        
	 	    selSkip=1
	     }

        # ===================================================================

	    [[ ${selSkip} -eq 0 ]] &&
	     {
            checkNewTemplate "${destTemplate}"
            ckResult=$?
            [[ ${ckResult} -eq 0 ]] && break

            # ===============================================================

            msgboxPositive="{$cklicons/yes.png}"
            msgboxNegative="{$cklicons/no.png}"
            msgboxIcon="$cklicons/warningred.png"
            
            messageBox "Over-writing of ${destTemplate} denied." "Destination denied."
            [[ $? -eq 0 ]] || ecName="overwrite"

	        [[ "${ecName}" == "none" ]] || break
         }

    done
    
    [[ "${ecName}" == "none" ]] || return 1

    return 0
}

# ===========================================================================
#
#    copyTemplate - copy a template to a new location/name
#
#    parameters:
#        none
#    returns:
#        0 = no error
#        non-zero = error code
#
# ===========================================================================
function copyTemplate()
{
	ecName="none"

    while [[ true ]]
    do
        getSourceTemplate
        [[ $? -eq 0 ]] || 
	     {
            [[ "${ecName}" == "none" ]] || ecName="getsrc-template"
            break
         }

        getDestinationTemplate
        [[ $? -eq 0 ]] || 
	     {
            [[ "${ecName}" == "none" ]] || ecName="getdest-template"
            break
         }

        # ===================================================================

        msgboxPositive="{$cklicons/yes.png}"
        msgboxNegative="{$cklicons/no.png}"
        msgboxIcon="$cklicons/question3.png"

        messageBox "Copy ${sourceTemplate} to ${destTemplate}" "Verify copy."
       	[[ $? -eq 0 ]] || ecName="cpyverify"

        [[ "${ecName}" == "none" ]] || break

        # ===================================================================

        [[ ${ckTemplateExists} -eq 1 ]] &&
         {
            msgboxPositive=" Remove Files"
            msgboxNegative="Merge Files"
            msgboxIcon="$cklicons/warningred.png"

            messageBox "Remove existing files prior to copy?" "Remove existing files or merge files."
            [[ $? -eq 0 ]] &&
             {
         	    rm -Rf "${destTemplate}"
         	    [[ $? -eq 0 ]] || ecName="rmfolder"
         	    break
             }
         }

        # ===================================================================

        mkdir -p "${destTemplate}"
        [[ $? -eq 0 ]] || 
         {
         	ecName="mkfolder"
            break
         }

        cp -rf ${sourceTemplate}/* ${destTemplate}
        [[ $? -eq 0 ]] || ecName="cpyfolder"

        break

    done

	[[ "${ecName}" == "none" ]] || return 1

    return 0
}

# ===========================================================================
# ===========================================================================
#
#   Script starts here
#
# ===========================================================================
# ===========================================================================

installTemplateErrors

copyTemplate
ckResult=$?

# ===========================================================================

[[ "${ecName}" == "none" ]] ||
 {
 	#
 	#    lookup errorName to get the ecIndex of errorName in lmsErrorCodes
 	#
 	lmsErrorCode "${ecName}" "ecIndex"
 	[[ $? -eq 0 ]] || 
 	 {
 	 	ecName="unknown"
 	 	lmsErrorCode "${ecName}" "ecIndex"
 	 }

 	errorMessage "${lmsErrorMessages[$ecName]}" "selectTemplate - Error (${ecIndex})"
    ckResult=$ecIndex
 }

# ===========================================================================

exit ${ckResult}
