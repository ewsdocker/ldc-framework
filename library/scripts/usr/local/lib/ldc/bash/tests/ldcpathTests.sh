#!/bin/bash

declare    test_Version="0.2.1"
declare    test_Name="ldcpathTests.sh"

# =========================================================================
# =========================================================================
#
#	ldcpathTests.sh
#		Run tests to qualify ldcpath.lib functions.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.2.1
# @copyright © 2019-2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-library
# @subpackage ldcpathTests
#
# =========================================================================
#
#	Copyright © 2019-2021. EarthWalk Software
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

# =========================================================================
#
#	Dependencies
#
# =========================================================================

. ../ldcExpectedResults.lib

. ../ldcconCli.lib
. ../ldcDeclare.lib
. ../ldcDisplay.lib
. ../ldcUtilities.lib
. ../ldcPath.lib

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
#						 11 = ldcpathErrors has no matching ldcpathErrorMsg key
#
# =========================================================================
function test_st_Path()
{
	lpathInit

	ldcDisplayTs "*********************************"
	ldcDisplayTs "START test_st_Path"
	ldcDisplayTs "*********************************"

	local    lPath="/usr/local/share"

	local -a ldctestEntries=()
	local    ldctestList=""

	local    lFound=0

	ldcStrExplode "${PATH}" ":" "ldctestEntries"
	
	local ldctestList=${ldctestEntries[@]}

	ldcExpectedResults "${ldctestList}" "${ldcpath_list}" 0
	testResult=$?

	[[ ${testResult} -eq 0 ]] &&
	 {
		ldcDisplayTs "Checking for \"$lPath\" in \"${PATH}\"."

		lpathExists "$lPath"
		lFound=$?

 		lmsg="    \"$lPath\""
	    [[ $lFound -eq 0 ]] || lmsg="$lmsg is not" 

        lmsg="$lmsg in \"$PATH\""
        [[ $lFound -eq 0 ]] && lmsg="$lmsg ... Deleting."

		ldcDisplayTs "$lmsg"

		[[ ${lFound} -eq 0 ]] &&
		 {
 			lpathDelete ${lPath}
 			[[ $? -eq 0 ]] || testResult=1

			case $testResult in
				0)
	 				ldcDisplayTs
					ldcDisplayTs "Committing PATH to .bashrc ."

					lpathCommit
					[[ $? -eq 0 ]] || testResult=2
					;;
					
				*)
					testResult=2
					;;
			esac

 			[[ ${testResult} -eq 0 ]] || ldcDisplayTs "Unable to delete \"$lPath\"."
	 	}

	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	#
	 	#	Add path element
	 	#

		ldcDisplayTs
		ldcDisplayTs "Setting new PATH entry: $lPath."

		lpathAdd "${lPath}"
		lFound=$?

		[[ ${lFound} -eq 0 ]] ||
		 {
		 	ldcDisplayTs "   Unable to set new PATH entry."
		 	testResult=2
		 }

		[[ ${testResult} -eq 0 ]] && ldcDisplayTs "   "$lPath" was added to "$PATH"."
	 }

	#
	#	commit the changes (write to .bashrc and re-source the file)
	#

	[[ ${testResult} -eq 0 ]] && 
	 {
	 	ldcDisplayTs
		ldcDisplayTs "Committing PATH to .bashrc ."

	 	lpathCommit
		[[ $? -eq 0 ]] || testResult=3

		[[ ${testResult} -eq 0 ]] || ldcDisplayTs "   Commit failed."

		[[ ${testResult} -eq 0 ]] && ldcDisplayTs "   Reloaded PATH=${ldcpath_path}"
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	ldcExpectedResults "${PATH}" "${ldcpath_path}" 0
		testResult=$?

		[[ ${testResult} -eq 0 ]] || ldcDisplayTs "   Path lists do NOT match"
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
		ldcDisplayTs
		ldcDisplayTs "Deleting added path element $lPath."
	
		lpathDelete "$lPath"
		[[ $? -eq 0 ]] || testResult=4

		[[ ${testResult} -eq 0 ]] || ldcDisplayTs "   Unable to delete \"$lPath\" from \"${ldcpath_list}\"."
	 }

	[[ ${testResult} -eq 0 ]] &&
	 {
	 	ldcDisplayTs
		ldcDisplayTs "Committing PATH to .bashrc ."

	 	lpathCommit
		[[ $? -eq 0 ]] || testResult=5

		[[ ${testResult} -eq 0 ]] || ldcDisplayTs "   Commit failed."

		[[ ${testResult} -eq 0 ]] && 
		 {
		 	ldcDisplayTs "   Reloaded PATH=${ldcpath_path}"

		 	ldcExpectedResults "${PATH}" "${ldcpath_path}" 0
			testResult=$?

			[[ ${testResult} -eq 0 ]] && ldcDisplayTs "   Actual PATH=${PATH}" || ldcDisplayTs "   Path lists do NOT match"
		 }
	 }

	[[ ${ldcpath_modified} -eq 0 ]] ||
	 {
		ldcDisplayTs
	 	ldcDisplayTs "*** Warning: ldcpath_modified is NOT zero!"
	 	testResult=6
	 }

	ldcDisplay
	ldcDisplayTs "Tests completed " 0 "n"
	[[ ${testResult} -eq 0 ]] && ldcDisplayTs "successfully." || ldcDisplayTs "with error #$testResult."
	ldcDisplayTs
	ldcDisplayTs "*********************************"
	ldcDisplayTs "END test_st_Path"
	ldcDisplayTs "*********************************"

	ldcDisplay

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

ldccli_optQuiet=${LDCOPT_QUIET}
ldccli_optDebug=${LDCOPT_DEBUG}
ldccli_optRemove=${LDCOPT_REMOVE}

clear

ldcDisplayTs "*********************************"
ldcDisplayTs "*********************************"
ldcDisplayTs
ldcDisplayTs "      ${test_Name}"
ldcDisplayTs "          Version: ${test_Version}"
ldcDisplayTs
ldcDisplayTs "*********************************"
ldcDisplayTs "*********************************"
ldcDisplay

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

[[ ${testResult} -eq 0 ]] || ldcDisplayTs "ldcpathTests failed: $testResult"

exit ${testResult}
