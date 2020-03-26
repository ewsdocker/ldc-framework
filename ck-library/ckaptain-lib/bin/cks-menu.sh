#!/bin/bash
# =========================================================================================
#
#  cks-menu
#
#      kaptain menu framework for creating, editing, 
#		saving and running Kaptain menu templates.
#
# =========================================================================================
#
# @author Jay Wheeler.
# @version 0.2.0
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-ckaptain
# @subpackage cks-menu
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
declare kaptain_menu_vers="0.2.0"

# =========================================================================================
# =========================================================================================
#
#    Get support libraries
#
# =========================================================================================
# =========================================================================================

. /usr/local/lib/lms/lmsconCli.lib
. /usr/local/lib/lms/lmsDisplay.lib

. /usr/local/lib/lms/ckExit.lib

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
#		pName = name of the ckaptain template file
#       pBin = path to the bin folder for executable ckaptain shell
#		pTemplate = path to the template folder
#		pResult = path to the result folder to store modified grammar
#		pCustom = path to the customized options file
#
#     Result:
#       0 = no error
#       non-zero = error code
# 
# =========================================================================================
function processTemplate()
{
	local pName="${1}"
	local pBin="${2}"
	local pTemplate="${3}"
	local pResult="${4}"
	local pCustom="${5}"

	[[ -z "${pName}" || -z "${pBin}" || -z "${$pTemplate}" || -z "${$pResult}"]] && return ${EC_REQUIRED}

    # =====================================================================================

    local lExec="${pBin}/${pName}"

	local lFooter="${pTemplate}/footer.ktf"
	local lHeader="${pTemplate}/header.kth"
	local lOptions="${pTemplate}/options.ktd"
	local lGrammar="${pTemplate}/grammar.ktg"

	local lrGrammar="${pResult}/grammar.ktg"
	local lrExec="${pResult}/${pName}.sh"

    #
    #   make sure the output directory exists
    #
    mkdir -p ${pResult}

	#
	#	load options settings and overlay custom settings, if they exist
	#
	. "${lOptions}"

	[[ -f "${pCustom}" ]] && . "${pCustom}"

	#
	#	read the grammar into lrGrammar.ktg
	#
    . "${lHeader}"
		. "${pGrammar}"
	. "${lFooter}"

	echo "${kaptain_grammar}" | sed -e 's/  */ /g' > "${lrGrammar}"

    #
	#    send lrGrammar file to kaptain to create the controls and layouts in kaptain_cmd
    #
    kaptain_cmd=$( /usr/bin/kaptain --test "${lrGrammar}" )

	#
    #   if lrGrammar is invalid, it will leave kaptain_cmd empty.
    #	however, the cancel button will return the same result.
	#
    [[ -z "${kaptain_cmd}" ]] && return ${EC_NONE}

    #
    #      Create a copy of the valid, processed grammar in ${lrExec}
    #
    cat ${lHeader} ${lrGrammar} ${lFooter} > ${lrExec}

    #
    #      Store the generated command line in ${lExec} as an executable script
    #
    echo "#!/bin/bash" > "${lExec}"
    [[ $? -eq 0 ]] || return ${EC_WGRAMMAR}

    echo "${kaptain_cmd}" >> "${lExec}"
    [[ $? -eq 0 ]] || return ${EC_WGRAMMAR}

    chmod +x "${lExec}"
    
    return ${EC_NONE}
}

# =========================================================================================
# =========================================================================================

processTemplate "${ckptName}"
ckExit $?

# =========================================================================================
# =========================================================================================
#
#			END OF APPLICATION
#
# =========================================================================================
# =========================================================================================
