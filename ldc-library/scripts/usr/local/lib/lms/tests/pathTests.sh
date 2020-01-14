#!/bin/bash

declare    test_Version="0.1.0"
declare    test_Name="lmspathTests.sh"

# =========================================================================
# =========================================================================
#
#	lmspathTests.sh
#		Run tests to qualify lmspath.lib functions.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-dev
# @subpackage lmspathTests
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

. /usr/local/lib/lms/lmsUtilities.lib
. /usr/local/lib/lms/lmsconCli.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsArchiveUtilities.lib

. /usr/local/lib/lms/lmsPath.lib

# =========================================================================

declare testResult=0

# =========================================================================
# =========================================================================
#
#	Support functions
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	expectedResults
#		(apologies for the RRPN format)
#
#	  Entry:
#		resultExpected = expected result
#		resultReceived = received result
#		resultEquality = 0 = equal | 1 = not equal
#	  Exit:
#		0 = true result
#		1 = false result
#
# =========================================================================
function expectedResults()
{
	local lExpected="${1}"
	local lReceived="${2}"
	local lEquality=${3:-"0"}

	lmsDisplayTs "    *****************************"
	lmsDisplayTs "    START expectedResults"
	lmsDisplayTs "    *****************************"

 	lmsDisplayTs "    Testing expected results: "
 	lmsDisplayTs "       Expected: \"${lExpected}\" "
 	lmsDisplayTs "       Received: \"${lReceived}\" "
 	lmsDisplayTs "       Equality: \"${lEquality}\" "

	local exResult=0

	[[ -z ${lExpected} || -z ${lReceived} ]] && 
	 {
	 	lmsDisplayTs "    *** Missing required parameter ***"
	 	exResult=1
	 }

	[[ "${lEquality}" != "0" && "${lEquality}" != "1" ]] && 
	 {
	 	lmsDisplayTs "    *** Equality (${lEquality}) is not numeric, or is out of range ***"
	 	exResult=2
	 }

	[[ ${exResult} -eq 0 ]] &&
	 {

		if [[ "${lEquality}" == "0" ]]
		then
			[[ "${lExpected}" == "${lReceived}" ]] || exResult=1
		else
			[[ "${lExpected}" != "${lReceived}" ]] || exResult=1
		fi
	 }
	
	lmsDisplayTs "       Result: ${exResult}"
	lmsDisplayTs "    *****************************"
	lmsDisplayTs "    END expectedResults"
	lmsDisplayTs "    *****************************"
	lmsDisplayTs

	return ${exResult}
}

# =========================================================================
# =========================================================================
#
#	Test structures
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	test_st_Path
#
#		Error codes: 	 10 = expectedResult failed
#						 11 = lmspathErrors has no matching lmspathErrorMsg key
#
# =========================================================================
function test_st_Path()
{
	lmsDisplayTs "*********************************"
	lmsDisplayTs "START test_st_Path"
	lmsDisplayTs "*********************************"

	local    lPath='${HOME}'"/.cargo/bin"
	local    oPath="${PATH}"
	local -a lEntries=()
	local    lFound=0

	lmsDisplayTs "Looking for \"$lPath\" in \"${PATH}\" (should fail)."
	lmsStrExplode ${PATH} ":" "lEntries"
	
	testResult=0

	rc_InPath ${lPath}
	lFound=$?

	[[ ${lFound} -eq 0 ]] &&
	 {
 		lmsDisplayTs "\"$lPath\" found in \"$PATH\", but should not be."
 		testResult=2
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	lmsDisplayTs "$lPath was not found."
		lmsDisplayTs "Setting new PATH entry."

		rc_Path ${lPath}
		lFound=$?

		[[ ${lFound} -eq 0 ]] ||
		 {
		 	lmsDisplayTs "Unable to set new PATH entry."
		 	testResult=3
		 }
		 
		[[ ${testResult} -eq 0 ]] && lmsDisplayTs $lPath" was found in "$PATH"."
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
		echo "export PATH=${oPath}" >> ${HOME}/.bashrc
 		source ${HOME}/.bashrc	
	 }

	[[ ${testResult} -eq 0 ]] || testResult+=29

	lmsDisplayTs "*********************************"
	lmsDisplayTs "END test_st_Path"
	lmsDisplayTs "*********************************"

	lmsDisplay

	return ${testResult}
}

# =========================================================================
# =========================================================================
#
#	Test functions
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	test_rc_Error
#
#		Testing structure: rustcError
#
#		Error codes: 	 10 = expectedResult failed
#						 11 = rustcErrors has no matching rustcErrorMsg key
#						 12 = rc_Error failed
#
# =========================================================================
function test_rc_Error()
{
	lmsDisplayTs "*********************************"
	lmsDisplayTs "START test_rc_Error"
	lmsDisplayTs "*********************************"

	local eList="${rustcErrors[@]}"

	testResult=0
	for lError in ${eList}
	do
		rc_Error "$lError"
		testResult=$?
		[[ ${testResult} -eq 0 ]] || break

		expectedResults ${lError} ${rustcError} "0"
		testResult=$?
		[[ ${testResult} -eq 0 ]] || break

	 	lmsDisplayTs "  ${lError} = \"${rustcErrorMsg}\""
		lmsDisplay
	done

	[[ ${testResult} -eq 0 ]] || testResult+=9

	lmsDisplayTs "*********************************"
	lmsDisplayTs "END test_rc_Error"
	lmsDisplayTs "*********************************"

	lmsDisplay

	return ${testResult}
}

# =========================================================================
# =========================================================================
#
#
#
# =========================================================================
# =========================================================================

testResult=0
testsRun=0

lmscli_optQuiet=${LMSOPT_QUIET}
lmscli_optDebug=${LMSOPT_DEBUG}
lmscli_optRemove=${LMSOPT_REMOVE}

clear

lmsDisplayTs "*********************************"
lmsDisplayTs "*********************************"
lmsDisplayTs
lmsDisplayTs "      ${test_Name}"
lmsDisplayTs "          Version: ${test_Version}"
lmsDisplayTs
lmsDisplayTs "*********************************"
lmsDisplayTs "*********************************"

lmsDisplay
lmsDisplay

# =========================================================================

while true
do

    # =====================================================================

	test_Path
	[[ ${testResult} -eq 0 ]] || break

    # =====================================================================

	break
done

# =========================================================================

exit ${testResult}
