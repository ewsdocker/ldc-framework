#!/bin/bash
# =========================================================================
#
#	iniTests.sh
#		Test performance of ldcIni.lib.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-library
# @subpackage ldcLibraryTests.sh
#
# =========================================================================
#
#	Copyright © 2021. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-library.
#
#   ewsdocker/ldc-library is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-library is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-library.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

. ../ldcBashVars.lib
. ../ldcCliParse.lib
. ../ldcColorDef.lib
. ../ldcDeclare.lib
. ../ldcDisplay.lib
. ../ldcDmpVar.lib
. ../ldcFactory.lib
. ../ldcIni.lib
. ../ldcStr.lib
. ../ldcTestUtilities.lib
. ../ldcUId.lib
. ../ldcUtilities.lib

# don't move this - depends on above libs to auto run when loaded.

. ../ldcScriptName.lib
. ../ldcTestUtilities.lib

# =========================================================================
# =========================================================================
#
#	Global declarations
#
# =========================================================================
# =========================================================================

declare    testTriplet=""

declare -i testResult=0


declare -i ldccli_optDebug=0
declare -i ldccli_optOverride=0
declare -i ldccli_optSilent=0

# =========================================================================
# =========================================================================
#
#	Library functions
#
# =========================================================================
# =========================================================================

# ****************************************************************************
#
#	testIniRead
#		Read the ini file into ldcIniBuffer
#
#	parameters:
#		iniFile = ini file name
#
#	returns:
#		0 = no errors
#		non-zero = error number
#
# ****************************************************************************
function testIniRead()
{
	[[ "${ldccli_optDebug}" -eq 0 ]] || bashLineLocation "" "" 1 "${td_colorDivider}********** Entering " "**********${td_colorNoColor}"

	local    inir_file=${1:-""}

	local    inir_expected=""
	local -i inir_line
	local -i inir_error=1

	ldcTestDisplay ""
	ldcTestBlock "${td_colorNoColor}${ldcclr_Bold}testIniRead${td_colorNoColor}"
	ldcTestDisplay ""

	[[ "${ldccli_optDebug}" -eq 2 ]] && 
	 {
		bashLineLocation "" "" 1 "${td_colorError}********** Parameters" "********** ${td_colorNoColor}"
		ldcDmpVarList "inir_"
	 }

	inir_error=1

	while [ true ]
	do
		ldcIniRPrefix "ldctest_sec_"
		[[ $? -eq 0 ]] || break

		(( inir_error++ ))

		ldcIniRead "${inir_file}"
		[[ $? -eq 0 ]] || break

		(( inir_error++ ))

		ldcIniLoadSections
		[[ $? -eq 0 ]] || break

		inir_error=0
		break
	done	

	[[ "${ldccli_optDebug}" -eq 0 ]] || 
	 {
		bashLineLocation "" "" 1 "${td_colorBold}********** Exiting" "(inir_error: ${inir_error}) **********${td_colorNoColor}"
		ldcDmpVarList "ldcini_"
		[[ -z "${ldcini_rprefix}" ]] || ldcDmpVarList "${ldcini_rprefix}"
		ldcDmpVarList "inir_"
	 }

	ldcIndent -2

	ldcTestDisplay ""
	ldcTestBlock "${td_colorNoColor}End ${ldcclr_Bold}testIniRead${td_colorNoColor}"

	ldcIndent -2

}

# =========================================================================
# =========================================================================
#
#   main script starts here
#
# =========================================================================
# =========================================================================

LMSOPT_DEBUG=0
LMSOPT_QUIET=0
LMSOPT_OVERRIDE=0

ldccli_shellParam["Debug"]=${LMSOPT_DEBUG}
ldccli_shellParam["Override"]=${LMSOPT_OVERRIDE}
ldccli_shellParam["Quiet"]=${LMSOPT_QUIET}

ldccli_optDebug=2

clear

testResult=0

# =========================================================================

td_indent=1

while [ true ]
do
#	testLine=$LINENO ; testIniRead ${1:-"../../../../etc/ldc/cliOptions.ini"}
	testLine=$LINENO ; ldcIni "../../../../etc/ldc/cliOptions.ini" 
	testResult=$?; [[ $? -eq 0 ]] ||
	 {
    	ldcTestDisplay "${td_colorError}iniTests failed @ $testTriplet ($testResult)${td_colorNoColor}"
 		break
	 }

	testResult=0
	break

done

ldcIndent -1
ldcTestDisplay "testResult = $testResult"

[[ "${ldccli_optDebug}" -eq 0 && ${testResult} -eq 0 ]] || 
 {
	bashLineLocation "" "" 1 "${td_colorError}" "(testResult: ${testResult})${td_colorNoColor}"
 }

# =========================================================================

exit $testResult
