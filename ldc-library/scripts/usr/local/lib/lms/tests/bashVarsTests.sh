#!/bin/bash
# =========================================================================
# =========================================================================
#
#	bashVarsTests.sh
#		Test performance of lmsBashVars.lib.
#			Specifically tests the 3 call paths:
#				- from the main script
#				- from within a function with direct call
#				- from within a function with indirect call (intermediate function)
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-dev
# @subpackage bashVarsTests.sh
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
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

. /usr/local/lib/lms/lmsBashVars.lib
. /usr/local/lib/lms/lmsconCli.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/expectedResults.lib
. /usr/local/lib/lms/lmsUtilities.lib

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
	lmsDisplayTs "*********************************"
	lmsDisplayTs "Start testBashLineLocation tests"
	lmsDisplayTs "*********************************"

	local -i tbllResult=0
	local    tExpected=""

#	local    tLine=$LINENO
#	let tLine=$tLine+2
#	bashLineLocation "testTriplet" 

	local -i tLine=$LINENO; bashLineLocation "testTriplet" 
	[[ $? -eq 0 ]] ||
	 {
		lmsDisplayTs "bashLineLocation failed @ $tLine"
		tbllResult=1
	 }

	[[ $tbllResult -eq 0 ]] &&
	 {
		tExpected="bashVarsTests:testBashLineLocation:$tLine"
	 	expectedResults "$tExpected" "$testTriplet" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs "expectedResults failed @ $tLine"
			tbllResult=2
		 }
	 }

	lmsDisplayTs "**********************************"
	lmsDisplayTs "End testBashLineLocation tests ($tbllResult)"
	lmsDisplayTs "**********************************"
	lmsDisplayTs

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

	lmsDisplayTs "*********************************"
	lmsDisplayTs "Start IndirectCall tests"
	lmsDisplayTs "*********************************"

	local -i tbicResult=0
	local    tExpected=""

	testTriplet=""
	bashLineLocation "testTriplet" "testIndirectCall"
	[[ $? -eq 0 ]] ||
	 {
		lmsDisplayTs "bashLineLocation failed @ $tbicLine"
		lmsDisplayTs "$testTriplet"
		tbicResult=1
	 }

	[[ $tbicResult -eq 0 ]] &&
	 {
		tExpected="bashVarsTests:main:$tbicLine"
	 	expectedResults "$tExpected" "$testTriplet" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs "expectedResults failed @ $tbicLine"
			tbicResult=2
		 }
	 }

	lmsDisplayTs "**********************************"
	lmsDisplayTs "End testIndirectCall tests ($tbicResult)"
	lmsDisplayTs "**********************************"
	lmsDisplayTs

	return $tbicResult
}

# =========================================================================
# =========================================================================
#
#   main script starts here
#
# =========================================================================
# =========================================================================

lmscli_optQuiet=${LMSOPT_QUIET}
lmscli_optDebug=${LMSOPT_DEBUG}
lmscli_optRemove=${LMSOPT_REMOVE}

# =========================================================================

lmsDisplayTs "****************************"
lmsDisplayTs "Start main tests"
lmsDisplayTs "****************************"

testLine=$LINENO
let testLine=$testLine+2
bashLineLocation "testTriplet" 
[[ $? -lt 2 ]] ||
 {
    lmsDisplayTs "bashLineLocation failed @ $testLine"
 	exit 1
 }

expectedResults "bashVarsTests:main:$testLine" "$testTriplet" 0
[[ $? -eq 0 ]] ||
 {
 	lmsDisplayTs "expectedResults failed @ $testLine"
 	exit 2
 }

lmsDisplayTs "****************************"
lmsDisplayTs "End main tests"
lmsDisplayTs "****************************"
lmsDisplayTs

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
