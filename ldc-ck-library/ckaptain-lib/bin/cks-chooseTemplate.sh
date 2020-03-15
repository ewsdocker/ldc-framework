#!/bin/bash
# =========================================================================================
#
#  cks-chooseTemplate
#
#      gui application to select and load a ckaptain template.
#
# =========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.6
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-ckaptain
# @subpackage cks-chooseTemplate
#
# =========================================================================
#
#	Copyright © 2017-2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-ckaptain.
#
#   ewsdocker/ldc-ckaptain is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-ckaptain is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-ckaptain.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================================
# =========================================================================================

fcDir="${KPT_MENU}"

declare    isNew=0
declare -a nv

# =========================================================================================
# =========================================================================================
#
#    Get environment and declared variables
#
# =========================================================================================
# =========================================================================================

. /usr/local/lib/lms/lmsStr.lib

. /usr/local/share/lms/ckaptain/cks-globalVars.lib

# =========================================================================================
# =========================================================================================

# =========================================================================================
#
#	cks-chooseFolder
#		parse the path to the template into
#           the path to the template folder and 
#           the name to the template folder name
#
#  	Parameters:
#		fullname = full path to the template
#       path     = location to store the path to the template folder (w/o template folder name)
#		name     = location to store the template folder name
#		sep      = (optional) path separator, defaults to "/"
#
#   Results:
#       0 = no error
#       non-zero = error code
#
# =========================================================================================
function cks-chooseFolder()
{
	[[ -z "${1}" || -z "${2}" || -z "${3}" ]] && return 1

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
#	cks-chooseTemplate
#
#	Parameters:
#		fcDir = directory to look for template folders in
#		fcTemplate = full name of the selected template folder
#
#   Results:
#       0 = no error, fcTemplate contains the selected template folder name
#       no-zero = error code
#
# ===========================================================================
function cks-chooseTemplate()
{
    local fcDir="${1}"
    local fcTName=""
    local fdTemp="./grmtemp"

    . ${ck-widget-dir}/ckw-chooseTemplate.widget
	[[ $? -eq 0 ]] || return 1

	cd ${fcDir}

    echo "${kaptain_grammar}" | sed -e 's/  */ /g' > "${fdTemp}"
    fcTName=$( /usr/bin/kaptain --test "${fdTemp}" )

    rm -f "${fdTemp}"
    cd

    [[ -z "${fcTName}" ]] && return 2
    
    #
    #	place fcTName into the input buffer and parse the name,
    #		format:
    #			new=template-name or
    #           file=template-name
    #
    read -ra cliBuffer <<< "${fcTName}"

    lmsCliParse
    [[ $? -eq 0 ]] ||
     {
         echo "Unable to parse the request string:"
         echo "'${fcTName}'"
         return 3
     }

    key=${cliKey[0]}
    param=${cliParam[$key]}

    [[ -z "${param}" ]] && return 4

    [[ "${key}" == "new" ]] && isNew=1 || isNew=0
#    [[ "${cliKey[0]}" == "new" ]] && isNew=1 || isNew=0

    cks-chooseFolder "$param" "${2}" "${3}" "/"
    [[ $? -eq 0 ]] || return 5

    return 0
}

# ===========================================================================
#
#	cks-checkTemplate
#       If template exists, make sure it can be overwritten.
#
#	Parameters:
#       None
#   Results:
#       0 = request allowed
#       1 = cancel request
#
# ===========================================================================
function cks-checkTemplate()
{
    local fdTemp="./grmtemp"

    [[ $isNew == 1 ]] &&
     {
        [[ -d "${fcFolder}/${fcTemplate}" ]] || return 0

        . ${ck-widget-dir}/ckw-checkOverwrite.widget
		[[ $? -eq 0 ]] || return 1

        echo "${kaptain_grammar}" | sed -e 's/  */ /g' > "${fdTemp}"
        result=$( /usr/bin/kaptain --test "${fdTemp}" )

        rm -f "${fdTemp}"

        case "$result" in 

            "over")  # create/overwrite - populate template folder w/ default template

                isNew=1
                rm "${fcFolder}/${fcTemplate}" > /dev/null 2>&1
                mkdir -p "${fcFolder}/${fcTemplate}"
                [[ $? -eq 0 ]] ||
                 {
                    echo "Unable to create template folder ${fcFolder}/${fcTemplate}"
                    return 2
                 }

			    #
			    #	replace w/code to pop-up screen to allow copy of template from
			    #      selected template to the new template folder
			    #
                CKPT_FILE="${KPT_DEFTEMPL}.kpt"
                KPT_FDEF="${KPT_DEFTEMPL}.ktd"

                cp "${KPT_FILE}" "${fcFolder}/${fcTemplate}/${fcTemplate}.kpt"
                [[ $? -eq 0 ]] && cp "${KPT_FDEF}" "${fcFolder}/${fcTemplate}/${fcTemplate}.ktd"
                [[ $? -eq 0 ]] ||
                 {
                    echo "Unable to copy default template to ${fcFolder}/${fcTemplate}"
                    return 3
                 }

                ;;

            "edit")  # edit - edit the template folder 

                isNew=0
                ;;

            *)  return 4
                ;;

        esac
     }
 
    return 0
 }


# ===========================================================================

cks-chooseTemplate $fcDir "fcFolder" "fcTemplate"
[[ $? -eq 0 ]] || 
 {
    echo "Cancelled."
    exit 1
 }

cks-checkTemplate
[[ $? -eq 0 ]] ||
 {
    echo "Cancelled."
    exit 2
 }
 
echo "isNew: ${isNew}"

[[ ${isNew} -eq 1 ]] &&
 {
    # first, delete any current data
    echo "new file: $fcFolder/$fcTemplate"
 }


exit 0
