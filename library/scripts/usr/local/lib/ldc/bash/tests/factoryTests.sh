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
. ../ldcCliParse.lib
. ../ldcColorDef.lib
. ../ldcDeclare.lib
. ../ldcDisplay.lib
. ../ldcDmpVar.lib
. ../ldcFactory.lib
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

declare -i testError=0


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

# =========================================================================
#
#    testFactoryInit
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
	local    facExpected=""
	local -i facLine
	local -i faclResult=1

	ldcIndent 2

	ldcTestDisplay ""
	ldcTestBlock "${td_colorNoColor}${ldcclr_Bold}testFactoryInit${td_colorNoColor}"
	ldcTestDisplay ""

	ldcIndent -2

	while [ true ]
	do

		# ========================================================

		ldcIndent 2

		ldcTestDisplay "${ldcclr_Bold}Calling ldcFactoryInit${td_colorNoColor}"
		ldcTestDisplay ""

		facLine=$LINENO; ldcFactoryInit 
		[[ $? -eq 0 ]] ||
		 {
			faclResult=$?

			ldcIndent 2
			ldcTestDisplay "Test Result = $faclResult"
			ldcIndent -2

			ldcTestDisplay ""

			ldcTestBlock "${td_colorError}ldcFactoryInit failed @ $facLine${td_colorNoColor}" "" "" "" "${td_colorError}"
			ldcTestDisplay ""
			ldcIndent -2
			
			break
		 }

		facExpected="1"

		ldcIndent 2
		ldcTestDisplay "${ldcclr_Bold}ldcFactoryInit returned ${td_colorDivider}${faclResult}${td_colorNoColor}"
		ldcIndent -2

		ldcTestDisplay ""
		ldcTestDisplay "${ldcclr_Bold}Validating ${td_colorError}ldcfac_initialized${td_colorNoColor}"

 		testExpectedResults "$facExpected" "$ldcfac_initialized" 0
		[[ $? -eq 0 ]] ||
		 {
			faclResult=$?
			ldcIndent -4
			ldcTestDisplay "${td_colorError}expectedResults failed @ $facLine${td_colorNoColor}"
			
			break
		 }

		ldcIndent -4
		ldcTestDisplay ""

		faclResult=0
		
		break
	done

	case ${faclResult} in

		0)
			ldcTestBlock "${ldcclr_Bold}testFactoryInit tests successfully completed.${ldcclr_NoColor}" "" "" 60 
			;;

		*)
		   	ldcTestBlock "${ldcclr_Bold}testFactoryInit test unsuccessful ($faclResult).${ldcclr_NoColor}" "" "" 60 
			;;

	esac

	ldcTestDisplay
	ldcTestDisplay "****************************"
	ldcTestDisplay "End Init Tests"
	ldcTestDisplay "****************************"

	return $faclResult
}

# =========================================================================
#
#    testFactory
#
#	parameters:
#		factory    = factory name
#		bridge     = bridge name
#		function   = bridge definition
#		addbridge  = 0 to lookup only, 1 to add
#		addfactory = 0 to lookup only, 1 to add
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function testFactory()
{
	[[ "${ldccli_optDebug}" -eq 0 ]] || bashLineLocation "" "" 1 "${td_colorDivider}********** Entering " "**********${td_colorNoColor}"

	local t_factory=${1:-""}
	local t_bridge=${2:-""}
	local t_function=${3:-""}

	local t_addbridge=${4:-"0"}
	local t_addfactory=${5:-"0"}

	local    facExpected=""
	local -i facLine
	local -i faclResult=0

	ldcTestDisplay ""
	ldcTestBlock "${td_colorNoColor}${ldcclr_Bold}testFactory${td_colorNoColor}"
	ldcTestDisplay ""

	[[ "${ldccli_optDebug}" -eq 2 ]] && 
	 {
		bashLineLocation "" "" 1 "${td_colorError}********** Parameters" "********** ${td_colorNoColor}"
		ldcDmpVarList "t_"
	 }

	while [ true ]
	do
		ldcIndent 2

		ldcTestDisplay "${td_colorNoColor}Calling ${ldcclr_Bold}ldcFactory${td_colorNoColor} to process the parameters"
		ldcTestDisplay

		#
		#	Call ldcFactory to perform the request
		#
		facLine=$LINENO; ldcFactory ${t_factory} ${t_bridge} ${t_function} ${t_addbridge} ${t_addfactory}
		facResult=$? ; [[ $facResult -eq 0 ]] ||
		 {
			ldcIndent 2
			ldcTestDisplay "Test Result = $faclResult"
			ldcIndent -2

			ldcTestDisplay ""

			ldcTestBlock "${td_colorError}ldcFactory failed @ $facLine${td_colorNoColor}" "" "" "" "${td_colorError}"

			ldcTestDisplay ""

			break
		 }
			
		facExpected="0"

 		testExpectedResults "$facExpected" "$faclResult" 0
		faclResult=$?

		ldcTestDisplay

		case $faclResult in

		 	0)
				ldcTestDisplay "${td_colorBold}ldcFactory completed successfully.${td_colorNoColor}"
				break
				;;
				
			*)
				ldcTestDisplay "${td_colorError}expectedResults failed @ $facLine ($faclResult)${td_colorNoColor}"
				break
				;;

		esac

		break
	done

	[[ "${ldccli_optDebug}" -eq 0 ]] || 
	 {
		bashLineLocation "" "" 1 "${td_colorBold}********** Exiting" "(faclResult: ${faclResult}) **********${td_colorNoColor}"
		ldcDmpVarList "ldcfac_"
	 }


	ldcIndent -2

	ldcTestDisplay ""
	ldcTestBlock "${td_colorNoColor}End ${ldcclr_Bold}testFactory${td_colorNoColor}"

	ldcIndent -2

	return $faclResult
}

# =========================================================================
#
#    testFactoryTests
#
#	parameters:
#		factory    = factory name
#		bridge     = bridge name
#		function   = bridge definition
#		addbridge  = 0 to lookup only, 1 to add
#		addfactory = 0 to lookup only, 1 to add
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function testFactoryTests()
{
	[[ "${ldccli_optDebug}" -eq 0 ]] || bashLineLocation "" "" 1 "${td_colorDivider}********** Entering " "**********${td_colorNoColor}"

	local tf_factory=${1:-""}
	local tf_bridge=${2:-""}
	local tf_function=${3:-""}

	local tf_addbridge=${4:-"0"}
	local tf_addfactory=${5:-"0"}

	local tf_error=1

	ldcTestBlock "${td_colorNoColor}${ldcclr_Bold}testFactoryTests${td_colorNoColor}"

	ldcIndent 2
	ldcTestDisplay "${td_colorNoColor}${ldcclr_Bold}Parameters:${td_colorNoColor}"

	ldcDmpVarList "tf_"
	[[ ${tf_addbridge} -eq 0 ]] && ldcDmpVarList "ldcfac_"

	while [ true ]
	do
		ldcTestDisplay "${td_colorNoColor}Calling ${ldcclr_Bold}testFactory${td_colorNoColor} to process the parameters"

		ldcIndent 2

		#
		#	Call testFactory to process the parameters
		#
		testLine=$LINENO; testFactory "${tf_factory}" "${tf_bridge}" "${tf_function}" "${tf_addbridge}" "${tf_addfactory}"
		[[ $? -eq 0 ]] ||
		 {
			tf_error=$?
			ldcTestDisplay "${td_colorError}testFactory failed @ $testLine ($tf_error)${td_colorNoColor}"
			break
		 }

		tf_error=0
		break
	done
	
	ldcTestDisplay ""
	[[ $tf_error -eq 0 ]] && ldcTestDisplay "${td_colorBold}testFactory completed successfully.${td_colorNoColor}"
	ldcTestDisplay ""

	ldcIndent -2

	ldcTestBlock "${td_colorNoColor}End ${ldcclr_Bold}testFactoryTests${td_colorNoColor}"

	[[ "${ldccli_optDebug}" -eq 0 ]] || 
	 {
		bashLineLocation "" "" 1 "${ldcclr_Bold}********** Exiting" "(tf_error: ${tf_error}) **********${ldcclr_NoColor}"
		ldcDmpVarList "tf_"
		ldcDmpVarList "ldcfac_"
	 }

	return $tf_error
}

# =========================================================================
#
#    testFactoryTestsLabel
#		Format a string containing description of test
#
#	parameters:
#		type    = "Add" or "Lookup"
#		factory = factory name
#		bridge  = bridge name
#
#    Result:
#        0 = no error
#
# =========================================================================
function testFactoryTestsLabel()
{
	tft_type=${1:-"Lookup"}
	tft_factory=${2:-""}
	tft_bridge=${3:-""}

	ldcTestDisplay
	ldcTestDisplay "${td_colorBorder}Calling ${ldcclr_Bold}testFactoryTests${ldcclr_NoColor}${td_colorBorder} to ${ldcclr_Bold}${tft_type}${ldcclr_NoColor}${td_colorBorder} Factory = ${ldcclr_Bold}${tft_factory}${ldcclr_NoColor}${td_colorBorder} and Bridge = ${ldcclr_Bold}${tft_bridge}${ldcclr_NoColor}" 
	ldcTestDisplay
}

# =========================================================================
#
#    runFactoryTests
#
#	parameters:
#		factory    = factory name
#		bridge     = bridge name
#		function   = bridge definition
#		addbridge  = 0 to lookup only, 1 to add
#		addfactory = 0 to lookup only, 1 to add
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function runFactoryTests()
{
	[[ "${ldccli_optDebug}" -eq 0 ]] || bashLineLocation "" "" 1 "${td_colorDivider}********** Entering " "**********${td_colorNoColor}"

	local    rf_factory=${1:-""}
	local    rf_bridge=${2:-""}
	local    rf_function=${3:-""}

	local -i rf_addbridge=1
	local -i rf_addfactory=1

	local -a rf_types=( "Add" "Lookup" "Validate")
	local    rf_type=""

	ldcTestDisplay
	ldcTestDivider 2 "${ldcclr_Bold}$ldcclr_Yellow" 60
	ldcTestDisplay

	ldcTestBlock "${td_colorNoColor}${ldcclr_Bold}runFactoryTests${td_colorNoColor}"

	ldcIndent 2

	for rf_type in "${rf_types[@]}"
	do

		[[ "${rf_type}" == "Validate" ]] && ldcFactoryReset

		[[ "${rf_type}" == "Add" ]] && rf_addbridge=1 || rf_addbridge=0

		testFactoryTestsLabel "${rf_type}" "${rf_factory}" "${rf_bridge}"

		ldcIndent 2

		testFactoryTests "${rf_factory}" "${rf_bridge}" "${rf_function}" ${rf_addbridge} ${rf_addfactory}
		testResult=$? ; [[ $testResult -eq 0 ]] || break

		ldcIndent -2

	done

	ldcTestDisplay ""
	[[ $tf_error -eq 0 ]] && ldcTestDisplay "${td_colorBold}testFactoryTests completed successfully.${td_colorNoColor}"

	ldcIndent -2

	ldcTestDisplay ""
	ldcTestBlock "${td_colorNoColor}End ${ldcclr_Bold}runFactoryTests${td_colorNoColor}"

	[[ "${ldccli_optDebug}" -eq 0 ]] || 
	 {
		bashLineLocation "" "" 1 "${ldcclr_Bold}********** Exiting" "(testResult: ${testResult}) **********${ldcclr_NoColor}"
		ldcDmpVarList "rf_"
		ldcDmpVarList "ldcfac_"
	 }

	ldcTestDisplay
	ldcTestDivider 2 "${ldcclr_Bold}$ldcclr_Yellow" 60
	ldcTestDisplay

	return $testResult
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

clear

testResult=0
ldcfac_initialized=0

# =========================================================================

td_indent=1

while [ true ]
do
	ldcTestDisplay "****************************"
	ldcTestDisplay "Start Init tests"
	ldcTestDisplay "****************************"

	ldcTestBlock "${ldcclr_Bold}ldcFactoryInit tests.${ldcclr_NoColor}"

	testLine=$LINENO ; testFactoryInit 
	[[ $? -eq 0 ]] ||
	 {
		testResult=$?
    	ldcTestDisplay "${td_colorError}testFactoryInit failed @ $testTriplet ($testResult)${td_colorNoColor}"
 		break
	 }

	# =====================================================================
	# =====================================================================
	#
	#		testFactory
	#
	# =====================================================================
	# =====================================================================

	ldcTestDisplay "****************************"
	ldcTestDisplay "Start Factory tests"
	ldcTestDisplay "****************************"

	ldcIndent 2
	
	ldcTestDisplay
	ldcTestDisplay "${ldcclr_Bold}Calls runFactoryTests to perform FACTORY and BRIDGE tests.${ldcclr_NoColor}"
	ldcTestDisplay

	ldcTestDisplay "${ldcclr_Bold}${ldcclr_NoColor}Testing ability to add and retrieve Factory(s) and Bridge(s)${ldcclr_NoColor}"

	ldcIndent 2

	  # ####################################################
	  #
	  #	Run Factory/Bridge tests
	  #
	  # ####################################################

	  runFactoryTests "HELP" "XML" "ldc_XML"
	  testResult=$? ; [[ $? -eq 0 ]] || break

	  # ####################################################

	  runFactoryTests "ERRORS" "INI" "ldc_INI"
	  testResult=$? ; [[ $? -eq 0 ]] || break

	  # ####################################################
	
	  runFactoryTests "HELP" "JQ" "ldc_JQ"
	  testResult=$? ; [[ $? -eq 0 ]] || break

	# ####################################################

	td_indent=2

	ldcTestDisplay
	ldcTestDisplay "****************************"
	ldcTestDisplay "End Factory Tests"
	ldcTestDisplay "****************************"

	# =====================================================================

	ldcTestDisplay

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
