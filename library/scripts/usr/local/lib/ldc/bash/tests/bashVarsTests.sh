#!/bin/bash
# =========================================================================
# =========================================================================
#
#	bashVarsTests.sh
#		Test performance of ldcBashVars.lib.
#			Specifically tests the 3 call paths:
#				- from the main script
#				- from within a function with direct call
#				- from within a function with indirect call (intermediate function)
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.2.0
# @copyright © 2019-2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-dev
# @subpackage bashVarsTests.sh
#
# =========================================================================
#
#	Copyright © 2019-2021. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-dev.
#
#   ewsdocker/ldc-dev is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-dev is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-dev.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

. ../ldcBashVars.lib
. ../ldcconCli.lib
. ../ldcDeclare.lib
. ../ldcDisplay.lib
. ../ldcExpectedResults.lib
. ../ldcStr.lib
. ../ldcUtilities.lib

# don't move this - depends on above libs to auto run when loaded.

. ../ldcScriptName.lib

# =========================================================================
# =========================================================================
#
#	Global declarations
#
# =========================================================================
# =========================================================================

declare    testTriplet=""
declare -i testline=0

declare -i testResults=0

# =========================================================================
# =========================================================================
#
#	Library functions
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#    testBashLineLocation - 
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function testBashLineLocation()
{
	ldcDisplayTs "*********************************"
	ldcDisplayTs "Start testBashLineLocation tests"
	ldcDisplayTs "*********************************"

	local -i tbllResult=0
	local    tExpected=""

	local -i tLine=$LINENO; bashLineLocation "testTriplet" 
	[[ $? -eq 0 ]] ||
	 {
		ldcDisplayTs "bashLineLocation failed @ $tLine"
		tbllResult=1
	 }

	[[ $tbllResult -eq 0 ]] &&
	 {
		tExpected="bashVarsTests:testBashLineLocation:$tLine"
	 	ldcExpectedResults "$tExpected" "$testTriplet" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs "expectedResults failed @ $tLine"
			tbllResult=2
		 }
	 }

	ldcDisplayTs "**********************************"
	ldcDisplayTs "End testBashLineLocation tests ($tbllResult)"
	ldcDisplayTs "**********************************"
	ldcDisplayTs

	return $tbllResult
}

# =========================================================================
#
#    testIndirectCall - 
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function testIndirectCall()
{
	local tbicLine=${1:-""}

	ldcDisplayTs "*********************************"
	ldcDisplayTs "Start IndirectCall tests"
	ldcDisplayTs "*********************************"

	local -i tbicResult=0
	local    tExpected=""

	testTriplet=""
	bashLineLocation "testTriplet" "testIndirectCall"
	[[ $? -eq 0 ]] ||
	 {
		ldcDisplayTs "bashLineLocation failed @ $tbicLine"
		ldcDisplayTs "$testTriplet"
		tbicResult=1
	 }

	[[ $tbicResult -eq 0 ]] &&
	 {
		tExpected="bashVarsTests:main:$tbicLine"
	 	ldcExpectedResults "$tExpected" "$testTriplet" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs "expectedResults failed @ $tbicLine"
			tbicResult=2
		 }
	 }

	ldcDisplayTs "**********************************"
	ldcDisplayTs "End testIndirectCall tests ($tbicResult)"
	ldcDisplayTs "**********************************"
	ldcDisplayTs

	return $tbicResult
}

# =========================================================================
# =========================================================================
#
#   main script starts here
#
# =========================================================================
# =========================================================================

ldccli_optQuiet=${LMSOPT_QUIET}
ldccli_optDebug=${LMSOPT_DEBUG}
ldccli_optRemove=${LMSOPT_REMOVE}

# =========================================================================

clear

ldcDisplayTs "****************************"
ldcDisplayTs "Start main tests"
ldcDisplayTs "****************************"

testLine=$LINENO
let testLine=$testLine+2
bashLineLocation "testTriplet" 
[[ $? -lt 2 ]] ||
 {
    ldcDisplayTs "bashLineLocation failed @ $testLine"
 	exit 1
 }

ldcExpectedResults "bashVarsTests:main:$testLine" "$testTriplet" 0
[[ $? -eq 0 ]] ||
 {
 	ldcDisplayTs "expectedResults failed @ $testLine"
 	exit 2
 }

ldcDisplayTs "****************************"
ldcDisplayTs "End main tests"
ldcDisplayTs "****************************"
ldcDisplayTs

# =========================================================================

testBashLineLocation
testResults=$?

# =========================================================================

testLine=$LINENO
let testLine=$testLine+2
testIndirectCall $LINENO
testResults=$?

# =========================================================================

exit $testResults
