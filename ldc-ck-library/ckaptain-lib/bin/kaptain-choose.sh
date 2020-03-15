#!/bin/bash
# =========================================================================================
#
#  choose-template
#
#      gui application to select and load a ckaptain template.
#
# =========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.6
# @copyright © 2017, 2018. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package debian-kaptain-select
# @subpackage choose-template
#
# =========================================================================
#
#	Copyright © 2018. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/debian-kaptain-select.
#
#   ewsdocker/debian-kaptain-select is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/debian-kaptain-select is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/debian-kaptain-select.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================================
# =========================================================================================
declare kaptain_menu_vers="0.1.6"

declare    isNew=0
declare -a nv

# =========================================================================================
# =========================================================================================
#
#    Get libraries
#
# =========================================================================================
# =========================================================================================

. /usr/local/lib/lms/lmsCli.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsStr.lib
. /usr/local/lib/lms/lmsUtilities.lib

. /opt/ckaptain-lib/lib/cklVars.lib
. /opt/ckaptain-lib/lib/cklWidget.lib

# =========================================================================================
# =========================================================================================

# =========================================================================================
#
#	folderName
#		return the folder name from provided parameters
#
#  	Parameters:
#		folder = path to template folder
#       path = location to store the path to the template folder (w/o template folder name)
#		name = location to store the template folder name
#		sep  = separator, default to "/"
#		
#   Results:
#       0 = no error
#       non-zero = error code
#
# =========================================================================================
function folderName()
{
    lmsStrExplode "${1}" ${4:-"/"} "nv"

    local elements=${#nv[@]}
    (( elements--  ))

    local path=""
    local index=1

    while [ ${index} -lt ${elements} ]
    do
        path="${path}/${nv[$index]}"
        (( index++ ))
    done
 
 	#
 	#	Store the path to the template folder (w/o the template folder name)
 	#
    lmsDeclareStr ${2} "${path}"
    [[ $? -eq 0 ]] || return 1

	#
	#	Store the name of the template folder
	#
    lmsDeclareStr ${3} "${nv[$index]}"
    [[ $? -eq 0 ]] || return 2

    return 0
}

# ===========================================================================
#
#	chooseTemplate
#
#	Parameters:
#		fcTDir    = directory to look for template folders
#       fcTDefault = default template name
#		fcTName   = location to store the template name
#		fcTFolder = location to store the processed command folder name
#
#   Results:
#       0 = no error
#       non-zero = error code
#
# ===========================================================================
function chooseTemplate()
{
    local fcTDir=${1}
    local fcTDefault=${2}

    [[ -z "${1}" || -z "${2}" || -z "${3}" || -z "${4}" ]] && return 1

	local oPwd=$PWD
	cd "${ck}"

    cklWidget "${fcTDir}/${fcTDefault}" "fcTemplate"
	[[ $? -eq 0 ]] || return 2

	cd $oPwd

    [[ -z "${fcTemplate}" ]] && return 3
    
    #
    # =======================================================================
    #
    #	place $fcTemplate into the input buffer and parse the name,
    #		format:
    #			new=template-name 
    #         or
    #           widget=template-name
    #
    #
    # =======================================================================
    #
    read -ra cliBuffer <<< "${fcTemplate}"

    lmsCliParse
    [[ $? -eq 0 ]] ||
     {
         echo "Unable to parse the request string: \"${fcTemplate}\""
         return 4
     }

    local fileType=${cliKey[0]}
    local filePath=${cliParam[$fileType]}

echo "fileType = $fileType"
echo "filePath = $filePath"

    [[ -z "${filePath}" ]] && return 5

    [[ "${fileType}" == "new" ]] && isNew=1 || isNew=0

    #
    # folderName $filePath $fileFolder $fileName $sep
    #
    #   filePath = path to template folder
    #   fileFolder = location to store the path to the template folder (w/o template folder name)
    #   fileName = location to store the template folder name
    #   sep  = (optional) separator, default to "/"

    folderName "${filePath}" "${3}" "${4}" "/"
    [[ $? -eq 0 ]] || return 6

    return 0
}

# ===========================================================================

#		fcDir = directory to look for template folders in
#		fcTName = full name of the selected template folder
#		fcWidget = location to store the template folder name

chooseTemplate "${ckw_widgets}" "ckw-chooseTemplate.widget" "templatePath" "templateName"
[[ $? -eq 0 ]] || 
 {
    echo "Cancelled."
    exit 1
 }

echo "templatePath = \"${templatePath}\""
echo "templateName = \"${templateName}\""

#checkTemplate
#[[ $? -eq 0 ]] ||
# {
#    echo "Cancelled."
#    exit 2
# }
 
echo "isNew: ${isNew}"

exit 0
