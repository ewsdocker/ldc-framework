#!/bin/bash
# =========================================================================================
#
#  kaptain-menu
#
#      kaptain menu framework for creating, editing, 
#		saving and running Kaptain menu templates.
#
# =========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.7
# @copyright © 2017, 2018, 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package debian-kaptain-menu
# @subpackage kaptain-menu
#
# =========================================================================
#
#	Copyright © 2017, 2018, 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/debian-kaptain-menu.
#
#   ewsdocker/debian-kaptain-menu is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/debian-kaptain-menu is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/debian-kaptain-menu.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================================
# =========================================================================================
declare kaptain_menu_vers="0.1.7"

# =========================================================================================
# =========================================================================================
#
#    Get support libraries
#
# =========================================================================================
# =========================================================================================

source /usr/local/lib/lms/lmsconCli.sh
source /usr/local/lib/lms/lmsconDisplay.sh

source /usr/local/lib/kaptain/errorExit.sh

source /usr/local/share/lms/kaptain/kaptain-menu-vars.lib

# =========================================================================================
# =========================================================================================
#
#			START OF APPLICATION
#
# =========================================================================================
# =========================================================================================

# =========================================================================================
#
#   processTemplate
#
#     Parameters:
#       none
#
#     Returns:
#       0 = no error
#       non-zero = error code
#
# =========================================================================================
function processTemplate()
{
	local kptName="${1}"

	[[ -z "${kptName}" ]] && return ${EC_REQUIRED}

    # =====================================================================================

    local kptExec="/command/${kptName}"

	local custTemplate="/custom/${kptName}/custom.ktg"

	local kptFooter="/lib/${kptName}/footer.ktf"
	local kptHeader="/lib/${kptName}/header.kth"
	local kptOptions="/lib/${kptName}/options.ktd"
	local kptTemplate="/lib/${kptName}/grammar.ktg"

	local sourceOptions="/source/${kptName}/options.ktd"
	local sourceTemplate="/source/${kptName}/grammar.ktg"
    local sourceRunOptions="/source/${kptName}/runoptions.ktd"

    # =====================================================================================
    #
    #     Load the template 
    #
    # =====================================================================================

    source "${kptTemplate}"

    # =====================================================================================
    #
    #    and apply dynamic variables
    #
    # =====================================================================================

    echo "${kaptain_grammar}" | sed -e 's/  */ /g' > "${sourceTemplate}"

    # =====================================================================================
    #
    #    Translate the kaptain_grammar into kaptain_cmd
    #      - if kaptain_grammar is invalid, it will be caught in the next step
    #
    # =====================================================================================

    kaptain_cmd=$( /usr/bin/kaptain --test "${sourceTemplate}" )

    [[ -z "${kaptain_cmd}" ]] && return ${EC_NONE}

    # =====================================================================================
    #
    #      Create a copy of the processed grammar in ${custTemplate}
    #
    # =====================================================================================

    cat ${kptHeader} ${sourceTemplate} ${kptFooter} > ${custTemplate}

    # =====================================================================================
    #
    #      Store the generated command line in ${kptExec} as an executable script
    #
    # =====================================================================================

    echo "#!/bin/bash" > "${kptExec}"
    [[ $? -eq 0 ]] || return ${EC_WRITE}

    echo "${kaptain_cmd}" >> "${kptExec}"
    [[ $? -eq 0 ]] || return ${EC_WBODY}

    chmod +x "${kptExec}"
    
    return ${EC_NONE}
}

# =========================================================================================
# =========================================================================================

processTemplate "${kptName}"
[[ $? -eq 0 ]] || errorExit $?

exit ${EC_NONE}

# =========================================================================================
# =========================================================================================
#
#			END OF APPLICATION
#
# =========================================================================================
# =========================================================================================
