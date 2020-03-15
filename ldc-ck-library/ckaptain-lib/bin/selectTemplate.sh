#!/bin/bash
# =========================================================================================
#
#  selectTemplate
#
#      gui application to select and load a ckaptain template.
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
declare selectTemplate_vers="0.1.0"

declare -i cklWidget_trace=0
declare -i selResult=0

declare -i newTemplate=0
declare -i newTemplateExists=0

# =========================================================================================
# =========================================================================================
#
#    Get libraries
#
# =========================================================================================
# =========================================================================================

. /usr/local/lib/lms/lmsConIn.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsStr.lib
. /usr/local/lib/lms/lmsUtilities.lib

. /opt/ckaptain-lib/lib/cklFileUtils.lib
. /opt/ckaptain-lib/lib/cklVars.lib
. /opt/ckaptain-lib/lib/cklWidget.lib

. /opt/ckaptain-lib/share/ckw/dialogs/messageBox.dialog

# =========================================================================================
# =========================================================================================

# ===========================================================================
#
#	checkNewTemplate
#
#	Parameters:
#       newTName = default template name
#
#   Results:
#       0 = no error
#       non-zero = error code
#
# ===========================================================================
function checkNewTemplate()
{
	local newTName="${1}"
    [[ -z "${newTName}" ]] && return 1

	newTemplateExists=0

    folderExists "${newTName}"
    [[ $? -eq 0 ]] &&
     {
       	newTemplateExists=1

	    msgboxPositive=" Continue"
	    msgboxNegative="Cancel"
	    msgboxResult="overwrite=${newTName}"
        msgboxIcon="$cklicons/warningred.png"

	 	messageBox "Overwrite ${newTName}?" "${newTName} Already Exists"
	 	[[ $? -eq 0 ]] || return 2
     }

     return 0
}

# ===========================================================================
#
#	selectTemplate
#
#	Parameters:
#       widgetType    = "source" (default) or "dest"
#		widgetDir     = directory to look for template folders
#       widgetDefault = default template name
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
    local widgetDir="${2}"
    local widgetDefault="${3}"

    [[ -z "${widgetDir}" || -z "${widgetDefault}" || -z "${4}" || -z "${5}" ]] && return 1

    # =======================================================================

	local oPwd=$PWD
	cd "${widgetDir}"

    cklWidget "${widgetDir}/${widgetDefault}" "widgetResult"
	[[ $? -eq 0 ]] || return 2

	cd $oPwd

    [[ -z "${widgetResult}" ]] && return 3

    # =======================================================================

	cliParam=()
	cliKey=()

	lmsConInSplit "${widgetResult}"
    [[ $? -eq 0 ]] ||
     {
         echo "Unable to parse the request string: \"${widgetResult}\""
         return 4
     }

    local fileType=${cliKey[0]}
    local filePath=${cliParam[$fileType]}

    [[ -z "${filePath}" ]] && return 5

    [[ "${fileType}" == "new" ]] && newTemplate=1 || newTemplate=0

    fileNameFromPath "${filePath}" "${4}" "${5}" "${ck}" "/"
    [[ $? -eq 0 ]] || return 7

    return 0
}

# ===========================================================================
#
#    getSourceTemplate - prompt for source template folder
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
    while [[ true ]]
    do
    	selResult=0

	    selsrcTitle="Select Source Template Folder"
        selsrcFolder="Source Folder Name: "

	    selectTemplate "source" "${ckw_widgets}" "ckwTSelectSrc.widget" "sourcePath" "sourceName"
	    selResult=$?
	    [[ $selResult -eq 0 ]] || break

	    sourceTemplate="${sourcePath}/${sourceName}"

	    selSkip=0

	    [[ "${sourceTemplate}" == "${ck}" ]] &&
	     {
	        msgboxPositive="{$cklicons/Tango/32x32/actions/go-previous.png}"
	        msgboxNegative="{$cklicons/Tango/32x32/actions/process-stop.png}"
            msgboxIcon="$cklicons/warningyellow.png"

	 	    messageBox "Invalid source: ${sourceTemplate}" "Invalid source."
	 	    selResult=$?
	        [[ $selResult -eq 0 ]] || break

	 	    selSkip=1
	     }

	    [[ ${selSkip} -eq 0 ]] &&
	     {
	        fileExists "${sourceTemplate}"
	        [[ $? -eq 0 ]] && 
	         {
                msgboxPositive="{$cklicons/yes.png}"
                msgboxNegative="{$cklicons/no.png}"
                msgboxIcon="$cklicons/question3.png"

                messageBox "Source = ${sourceTemplate}" "Verify source selection."
                selResult=$?
	            [[ $selResult -eq 0 ]] && break
	         }

            msgboxPositive="{$cklicons/Tango/32x32/actions/go-previous.png}"
	        msgboxNegative="{$cklicons/Tango/32x32/actions/process-stop.png}"
            msgboxIcon="$cklicons/question3.png"

            messageBox "Source folder ${sourceTemplate} does not exist." "Retry."
            selResult=$?
	        [[ $selResult -eq 0 ]] || break
         }
    done

    return $selResult
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
    while [[ true ]]
    do
	    seldestDir=${ck}

	    selectTemplate "dest" "${ckw_widgets}" "ckwTSelectDest.widget" "destPath" "destName"
	    selResult=$?
	    [[ $selResult -eq 0 ]] || break

echo "destPath = $destPath"
echo "destName = $destName"

        destTemplate="${destPath}"
echo "destTemplate = $destTemplate"

        [[ -z "${destName}" ]] || destTemplate="${destTemplate}/${destName}"
echo "destTemplate = $destTemplate"

	    selSkip=0

	    [[ -z "${destTemplate}" || "${destTemplate}" == "${ck}" || "${destTemplate}" == "${sourceTemplate}" ]] &&
	     {
            msgboxPositive="{$cklicons/Tango/32x32/actions/go-previous.png}"
	        msgboxNegative="{$cklicons/Tango/32x32/actions/process-stop.png}"
            msgboxIcon="$cklicons/warningred.png"
            
	 	    messageBox "${destTemplate}" "Invalid destination."
            selResult=$?
	        [[ $selResult -eq 0 ]] || break
	        
	 	    selSkip=1
	     }

	    [[ ${selSkip} -eq 0 ]] &&
	     {
            checkNewTemplate "${destTemplate}"
            selResult=$?
            [[ ${selResult} -eq 0 ]] && break

            msgboxPositive="{$cklicons/yes.png}"
            msgboxNegative="{$cklicons/no.png}"
            msgboxIcon="$cklicons/warningred.png"
            
            messageBox "Over-writing of ${destTemplate} denied." "Destination denied."
            selResult=$?
	        [[ $selResult -eq 0 ]] || break
         }
    done
    
    return ${selResult}
}

# ===========================================================================
# ===========================================================================

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
    while [[ true ]]
    do
        getSourceTemplate
          selResult=$?
          [[ $selResult -eq 0 ]] || break

        getDestinationTemplate
       	  selResult=$?
          [[ $selResult -eq 0 ]] || break

        msgboxPositive="{$cklicons/yes.png}"
        msgboxNegative="{$cklicons/no.png}"
        msgboxIcon="$cklicons/question3.png"

        messageBox "Copy ${sourceTemplate} to ${destTemplate}" "Verify copy."
       	  selResult=$?
          [[ $selResult -eq 0 ]] || break

        [[ ${newTemplateExists} -eq 1 ]] &&
         {
            msgboxPositive=" Remove Files"
            msgboxNegative="Merge Files"
            msgboxIcon="$cklicons/warningred.png"

            messageBox "Remove existing files prior to copy?" "Remove existing files or merge files."
            [[ $? -eq 0 ]] &&
             {
         	    rm -Rf "${destTemplate}"
         	    selResult=$?
             }
         }

        [[ $selResult -eq 0 ]] || break

        mkdir -p "${destTemplate}"
          selResult=$?
          [[ $selResult -eq 0 ]] || break

        cp -rf ${sourceTemplate}/* ${destTemplate}
          selResult=$?

        break

    done

    return ${selResult}
}

# ===========================================================================

copyTemplate

exit ${selResult}
