#!/bin/bash
# =========================================================================
#
#	ldcFactoryTests.sh
#		Test performance of ldcFactory.lib.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-library
# @subpackage ldcFactoryTests.sh
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
. ../ldcconCli.lib
. ../ldcColorDef.lib
. ../ldcDeclare.lib
. ../ldcDisplay.lib
. ../ldcExpectedResults.lib
. ../ldcFactory.lib
. ../ldcStr.lib
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


# =========================================================================
# =========================================================================
#
#	Library functions
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#    testFactoryInitialize - 
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function testFactoryInit()
{
	ldcDisplayTs "*********************************"
	ldcDisplayTs "Start testFactoryInit tests"
	ldcDisplayTs "*********************************"

	local    facExpected=""
	local -i facLine
	local -i faclResult=1

	while [ true ]
	do

		# ========================================================

		facLine=$LINENO; ldcFactoryInit 
		[[ $? -eq 0 ]] ||
		 {
			faclResult=$?
			ldcDisplayTs "${td_colorError}ldcFactoryInit failed @ $facLine${td_colorNoColor}"
			break
		 }

		facExpected="1"

 		ldcExpectedResults "$facExpected" "$faclResult" 0
		[[ $? -eq 0 ]] ||
		 {
			faclResult=$?
			ldcDisplayTs "${td_colorError}expectedResults failed @ $facLine${td_colorNoColor}"
			break
		 }

		faclResult=0
		break
	done

	ldcDisplayTs
	ldcDisplayTs "**********************************"
	ldcDisplayTs "End testFactoryInit tests ($faclResult)"
	ldcDisplayTs "**********************************"
	ldcDisplayTs

	return $faclResult
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

testResult=0
ldcfac_initalized=0

ldcDisplayTs "****************************"
ldcDisplayTs "Start main tests"
ldcDisplayTs "****************************"

while [ true ]
do
	testLine=$LINENO; bashLineLocation "testTriplet" 
	[[ $? -eq 0 ]] ||
	 {
		testResult=$?
		ldcDisplayTs "${td_colorError}bashLineLocation failed @ $testLine ($testResult)${td_colorNoColor}"
		break
	 }

	testExpected="factoryTests:main:$testLine"

	ldcExpectedResults "$testExpected" "$testTriplet" 0
	[[ $? -eq 0 ]] ||
	 {
		testResult=$?
		ldcDisplayTs "${td_colorError}expectedResults failed @ $testLine ($testResult)${td_colorNoColor}"
		break
	 }

    # =====================================================================

	ldcDisplayTs ""
	ldcDisplayTs "${td_colorDivider}*********************************${td_colorNoColor}"
	ldcTestDisplay
		ldcIndent 1
	    ldcTestDisplay "${td_colorDivider}Testing ldcFactoryInit${td_colorNoColor}"
		ldcIndent 0
	ldcTestDisplay ""
	ldcDisplayTs "${td_colorDivider}*********************************${td_colorNoColor}"

	testLine=$LINENO ; testFactoryInit 
	[[ $? -eq 0 ]] ||
	 {
		testResult=$?
    	ldcDisplayTs "${td_colorError}testFactoryInit failed @ $testLine ($testResult)${td_colorNoColor}"
 		break
	 }

#	ldcExpectedResults 0 "$testResult" 0
#	[[ $? -eq 0 ]] ||
#	 {
#		testResult=$?
# 		ldcDisplayTs "${td_colorError}expectedResults failed @ $testLine ($testResult)${td_colorNoColor}"
# 		break
#	 }

	testResult=0
	break
done

ldcDisplayTs
ldcDisplayTs "****************************"
ldcDisplayTs "End main tests"
ldcDisplayTs "****************************"
ldcDisplayTs

# =========================================================================

exit $testResult
