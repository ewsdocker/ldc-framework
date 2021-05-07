#!/bin/bash
# *****************************************************************************
# *****************************************************************************
#
#   	nsTests.sh
#
# *****************************************************************************
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package Linux Management Scripts
# @subpackage tests
#
# *****************************************************************************
#
#	Copyright © 2021. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-bash.
#
#   ewsdocker/ldc-bash is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-bash is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-bash.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# *****************************************************************************
#
#		Version 0.0.1 - 05-02-2021.
#
# *****************************************************************************
# *****************************************************************************

. ../ldcBashVars.lib
. ../ldcCliParse.lib
. ../ldcColorDef.lib
. ../ldcDeclare.lib
. ../ldcDisplay.lib
. ../ldcDmpVar.lib
. ../ldcFactory.lib
. ../ldcIni.lib
. ../ldcNs.lib
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

# *********************************************************************************
#
#	testNsSet
#
#		sets the value of a global variable
#
#	parameters:
#		name = name of global variable
#		value = value to set
#
#	returns:
#		0 = no error
#		1 = variable name is a number
#		2 = unable to set value
#
# *********************************************************************************
function testNsSet()
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

# *********************************************************************************
#
#	testNsInt
#
#		creates a global integer variable and sets it's value
#
#	parameters:
#		name = name of global variable
#		content = value to set
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsInt()
{
	[[ -z "${1}" || -z "${2}" ]] && return 1

	ldcDeclareInt "$ldcns_current${1}" "${2}"
	return $?
}

# *********************************************************************************
#
#	testNsStr
#
#		creates a global string variable and sets it's value
#
#	parameters:
#		name = name of global variable
#		value = value to set
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsStr()
{
	[[ -z "${1}" ]] && return 1

	ldcDeclareStr "$ldcns_current${1}" "${2}"
	return $?
}

# *********************************************************************************
#
#	testNsPwd
#
#		creates a global string password variable and sets it's value
#
#	parameters:
#		name = name of global variable
#		value = value to set
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsPwd()
{
	[[ -z "${1}" || -z "${2}" ]] && return 1

	ldcDeclarePwd "$ldcns_current${1}" "${2}"
	return $?
}

# *********************************************************************************
#
#	testNsAssoc
#
#		creates a global associative array variable
#
#	parameters:
#		name = name of global variable
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsAssoc()
{
	[[ -z "${1}" ]] && return 1

	ldcDeclareAssoc "$ldcns_current${1}"
	return $?
}

# *********************************************************************************
#
#	testNsArray
#
#		creates a global array variable
#
#	parameters:
#		name = name of global variable
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsArray()
{
	[[ -z "${1}" ]] && return 1

	ldcDeclareArray "$ldcns_current${1}"
	return $?
}

# *********************************************************************************
#
#	testNsArrayEl
#
#		Adds an element to a (global) associative array variable
#
#	parameters:
#		parent = global array variable
#		name = element name or index number
#		value = value to set
#
#	returns:
#		0 = no error
#		non-zero = error code
#
# *********************************************************************************
function testNsArrayEl()
{
	[[ -z "${1}" || -z "${2}" ]] && return 1

	ldcDeclareArrayEl "$ldcns_current${1}" "${2}" "${3}"
	return $?
}

# *********************************************************************************
#
#	testNsGetName
#
#	parameters:
#		name = global name (with no namespace)
#       nsName = store namespace variable name here
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsGetName()
{
	[[ -z "${1}" ]] && return 1

	ldcDeclareStrNs "${2}" "${ldcns_current}${1}"
	return $?
}

# *********************************************************************************
#
#	testNsGetValue
#
#	parameters:
#		name = global name (with no namespace)
#       nsName = store namespace variable value here
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNsGetValue()
{
	[[ -z "${1}" ]] && return 1

	eval "${2}='${ldcns_current}${1}'"
	return $?
}

# *********************************************************************************
#
#	testNs
#		Declare and make current the specifiec namespace
#
#	parameters:
#		ns = namespace to select
#
#	returns:
#		result = 0 if set ok
#			   = 1 if set error
#
# *********************************************************************************
function testNs()
{
	[[ -z "${1}" ]] && return 1

	ldcns_current=${1:-""}
	return 0
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
	testLine=$LINENO ; ldcIni "read" "../../../../etc/ldc/cliOptions.ini" "ldctest_" 
	testResult=$?; [[ $? -eq 0 ]] ||
	 {
    	ldcTestDisplay "${td_colorError}iniTests failed @ $testTriplet ($testResult)${td_colorNoColor}"
 		break
	 }

	testResult=0
	break

done

ldcIndent -1
[[ "${ldccli_optDebug}" -eq 0 && ${testResult} -eq 0 ]] || 
 {
	bashLineLocation "" "" 1 "${td_colorError}" "(testResult: ${testResult})${td_colorNoColor}"
 }

# =========================================================================

exit $testResult


# *****************************************************************************
