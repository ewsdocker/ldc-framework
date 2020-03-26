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

# =========================================================================
#
#	Dependencies
#
# =========================================================================

. /usr/local/lib/lms/expectedResults.lib
. /usr/local/lib/lms/lmsconCli.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsUtilities.lib
. /usr/local/lib/lms/lmsPath.lib

# =========================================================================
# =========================================================================
#
#	global declarations
#
# =========================================================================
# =========================================================================

declare testResult=0

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
	lpathInit

	lmsDisplayTs "*********************************"
	lmsDisplayTs "START test_st_Path"
	lmsDisplayTs "*********************************"

	local    lPath='${HOME}'"/.cargo/bin"

	local -a lmstestEntries=()
	local    lmstestList=""

	local    lFound=0

	lmsStrExplode "${PATH}" ":" "lmstestEntries"
	
	local lmstestList=${lmstestEntries[@]}

	expectedResults "${lmstestList}" "${lmspath_list}" 0
	testResult=$?

	[[ ${testResult} -eq 0 ]] &&
	 {
		lmsDisplayTs "Checking for \"$lPath\" in \"${PATH}\"."

		lpathExists "$lPath"
		lFound=$?

		[[ ${lFound} -eq 0 ]] &&
		 {
 			lmsDisplayTs "\"$lPath\" found in \"$PATH\"... Deleting."

 			lpathDelete ${lPath}
 			[[ $? -eq 0 ]] || testResult=1

 			[[ ${testResult} -eq 0 ]] || lmsDisplayTs "Unable to delete \"$lPath\"."
	 	}
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	#
	 	#	Add path element
	 	#

		lmsDisplayTs
		lmsDisplayTs "Setting new PATH entry: $lPath."

		lpathAdd '${lPath}'
		lFound=$?

		[[ ${lFound} -eq 0 ]] ||
		 {
		 	lmsDisplayTs "   Unable to set new PATH entry."
		 	testResult=2
		 }

		[[ ${testResult} -eq 0 ]] && lmsDisplayTs "   "$lPath" was added to "$PATH"."
	 }

	#
	#	commit the changes (write to .bashrc and re-source the file)
	#

	[[ ${testResult} -eq 0 ]] && 
	 {
	 	lmsDisplayTs
		lmsDisplayTs "Committing PATH to .bashrc ."

	 	lpathCommit
		[[ $? -eq 0 ]] || testResult=3

		[[ ${testResult} -eq 0 ]] || lmsDisplayTs "   Commit failed."

		[[ ${testResult} -eq 0 ]] && lmsDisplayTs "   Reloaded PATH=${lmspath_path}"
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	expectedResults "${PATH}" "${lmspath_path}" 0
		testResult=$?

		[[ ${testResult} -eq 0 ]] || lmsDisplayTs "   Path lists do NOT match"
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
		lmsDisplayTs
		lmsDisplayTs "Deleting added path element $lPath."
	
		lpathDelete "$lPath"
		[[ $? -eq 0 ]] || testResult=4

		[[ ${testResult} -eq 0 ]] || lmsDisplayTs "   Unable to delete \"$lPath\" from \"${lmspath_list}\"."
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	lmsDisplayTs
		lmsDisplayTs "Committing PATH to .bashrc ."

	 	lpathCommit
		[[ $? -eq 0 ]] || testResult=5

		[[ ${testResult} -eq 0 ]] || lmsDisplayTs "   Commit failed."

		[[ ${testResult} -eq 0 ]] && 
		 {
		 	lmsDisplayTs "   Reloaded PATH=${lmspath_path}"

		 	expectedResults "${PATH}" "${lmspath_path}" 0
			testResult=$?

			[[ ${testResult} -eq 0 ]] && lmsDisplayTs "   Actual PATH=${PATH}" || lmsDisplayTs "   Path lists do NOT match"
		 }
	 }

	[[ ${lmspath_modified} -eq 0 ]] ||
	 {
		lmsDisplayTs
	 	lmsDisplayTs "*** Warning: lmspath_modified is NOT zero!"
	 	testResult=6
	 }

	lmsDisplay
	lmsDisplayTs "Tests completed " 0 "n"
	[[ ${testResult} -eq 0 ]] && lmsDisplayTs "successfully." || lmsDisplayTs "with error #$testResult."
	lmsDisplayTs
	lmsDisplayTs "*********************************"
	lmsDisplayTs "END test_st_Path"
	lmsDisplayTs "*********************************"

	lmsDisplay

	[[ ${testResult} -eq 0 ]] || testResult+=9
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

# =========================================================================

while true
do

    # =====================================================================

	test_st_Path
	[[ ${testResult} -eq 0 ]] || break

    # =====================================================================

	break
done

# =========================================================================

[[ ${testResult} -eq 0 ]] || lmsDisplayTs "lmspathTests failed: $testResult"

exit ${testResult}
