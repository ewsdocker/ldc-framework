#!/bin/bash
# =========================================================================
# =========================================================================
#
#	ldcParseColumnTests.sh
#		Test performance of ldcParseColumn.lib.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.2.0
# @copyright © 2019-2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-utilities
# @subpackage ldcParseColumnTests.sh
#
# =========================================================================
#
#	Copyright © 2019-2021. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-utilities.
#
#   ewsdocker/ldc-utilities is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-utilities is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-utilities.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================
#
#	Dependencies:
#
#		ldcBashVars.lib
#		ldcconCli.lib
#		ldcColorDef.lib
#		ldcDeclare.lib
#		ldcDisplay.lib
#		ldcDockerQuery.lib
#		ldcParseColumns.lib
#		ldcScriptName.lib
#		ldcStr.lib
#		ldcTestUtilities.lib
#		ldcUtilities.lib
#
#		parseColumnTests.cfg
#
# =========================================================================
#
#	Functions:
#
#		pctMapLookup
#			test:	Lookup column name by provided index (defaults to pct_index). 
#					Returns 0 if found, 1 if not..
#
#		pctMapFind
#			test:	Lookup column name. Returns 0 if found, 1 if not..
#
#		pctMapList
#			test:	locates the name of the field following the specified 
#					field in cstatus[0] returns the index.
#
#		pctMapCheck
#			test:	locates the name of the field following the specified 
#					field in cstatus[0] returns the index.
#
#		pctMap
#			test:	locates the name of the field following the specified 
#					field in cstatus[0] returns the index.
#
#		pctMapColumn
#			test:	locates the column mapping and returns the index.
#
#		pctRun
#			Runs pctMapColumn, pctMap, pctFind and pctMapList in order.
#
# =========================================================================
# =========================================================================

declare    pct_version="0.2.0"

declare -i pct_optDev=1
declare -i pct_optDcl=0

declare    pct_libRoot="."
[[ ${pct_optDev} -eq 0 ]] || pct_libRoot="../ldc-utilities"

# =========================================================================
#
#		Load Libraries
#
# =========================================================================

. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcBashVars.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcconCli.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcColorDef.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcDeclare.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcDisplay.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcDockerQuery.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcParseColumns.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcScriptName.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcStr.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcTestUtilities.lib
. ${pct_libRoot}/scripts/usr/local/lib/ldc/ldcUtilities.lib

# =========================================================================
# =========================================================================
#
#	Global declarations
#
# =========================================================================
# =========================================================================

declare -i pct_results=0
declare -i pct_fieldindex=0

# =====================================================================
#
#		Load parseColumnTests configuration
#
# =====================================================================

. ${pct_libRoot}/scripts/usr/local/share/ldc/config/parseColumnTests.cfg

# =====================================================================

declare    ANCHOR="${pct_colorText}pc"

# =========================================================================
# =========================================================================
#
#	Library functions
#
# =========================================================================
# =========================================================================

# =========================================================================================
#
#	pctMapLookup
#		test:	Lookup column name by provided index (defaults to pct_index). 
#					Returns 0 if found, 1 if not..
#
#	parameters:
#		match = 0 for true match, 1 for false match
#		index = (optional) element to match (defaults to pct_index)
#
#    Returns:
#        0 = no error
#		 non-zero = error code
#
# =========================================================================================
function pctMapLookup()
{
	pct_expected=${1:-"0"}
	pct_match=${2:-"0"}
	pct_index=${3:-"$pct_index"}

	local -i pct_found=1
	bashLineLocation ; pcMapFind "${pc_column[$pct_index]}"
	pct_found=$?

	testExpectedResults $pct_expected $pct_found "$pct_match"
	[[ $? -eq 0 ]] ||
	 {
		ldcDisplayTs ""
		ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
		pct_results=1

		[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
		 {
 			declare -p | grep "pc_"
 			declare -p | grep "pct_"
 			ldcDisplay
	 	 }
	  }

	return ${pct_results}
}

# =========================================================================================
#
#	pctMapFind
#
#		test:	Lookup column name. Returns 0 if found, 1 if not..
#
#	parameters:
#		none
#
#    Returns:
#        0 = no error
#		 non-zero = error code
#
# =========================================================================================
function pctMapFind()
{
	ldcTestDisplay "${pct_colorBlock}Map Find${ldcclr_NoColor}"
	ldcDisplayTs ""

	(( td_indent+=2 ))

	pct_results=0

	while [ true ]
	do

		# =========================================================================

		ldcTestDisplay "${pct_colorText}Checking for Column Names:${ldcclr_NoColor}"
		ldcDisplayTs ""

		pct_index=0
		while [ $pct_index -lt ${#pc_column[*]} ]
		do
			bashLineLocation ; pctMapLookup "0" "0" $pct_index 

			ldcTestDisplay "${pct_colorText} pct_index = ${pct_index}: \"${pc_column[$pct_index]}\"${ldcclr_NoColor}"
			ldcDisplayTs ""
			
			(( pct_index++ ))
		done

		[[ $pct_results -eq 0 ]] || break

		# =========================================================================

		ldcTestDisplay "${pct_colorText}Checking for Bad Column Name${ldcclr_NoColor}"
		ldcDisplayTs ""

		pct_index=${#pc_column[*]}
		bashLineLocation ; pctMapLookup "1" "0" $pct_index

		[[ $pct_results -eq 0 ]] || break
		ldcTestDisplay "${pct_colorText} pct_index = ${pct_index}: \"${pct_fieldtitle[$pct_index]}\"${ldcclr_NoColor}"

		# =========================================================================

		break
	done
	
	ldcDisplayTs ""

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${pct_results}
}

# =========================================================================================
#
#	pctMapList
#
#		test:	locates the name of the field following the specified field in cstatus[0] 
#				returns the index.
#
#	parameters:
#		none
#
#    Returns:
#        0 = no error
#		 non-zero = error code
#
# =========================================================================================
function pctMapList()
{
	ldcTestDisplay "${pct_colorBlock}Map Listing  [ TITLE = START COLUMN : TITLE WIDTH : DATA WIDTH ]${ldcclr_NoColor}"
	ldcDisplayTs ""

	(( td_indent+=2 ))

	pct_index=0
	while [ ${pct_index} -lt ${#pc_column[*]} ]
	do
		ldcTestDisplay "${pct_colorText}${pc_column[$pct_index]} = ${pc_colstart[$pct_index]} : ${pc_collen[$pct_index]} : ${pc_coldata[$pct_index]}${ldcclr_NoColor}"
		(( pct_index++ ))
	done
	
	ldcDisplayTs ""

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${pct_results}
}

# =========================================================================================
#
#	pctMapCheck
#
#		test:	locates the name of the field following the specified field in cstatus[0] 
#				returns the index.
#
#	parameters:
#		none
#
#    Returns:
#        0 = no error
#		 non-zero = error code
#
# =========================================================================================
function pctMapCheck()
{
	ldcTestDisplay "${pct_colorBlock}Checking corresponding values for index: $pct_index${ldcclr_NoColor}"
	ldcDisplayTs ""

	(( td_indent+=2 ))

	while [ true ]
	do
		ldcTestDisplay "${pct_colorText}Checking for proper title:${ldcclr_NoColor}"
		ldcDisplayTs ""

		testExpectedResults "${pct_fieldtitle[$pct_index]}" "${pc_column[$pct_index]}" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=2

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
		 		declare -p | grep "pc_"
		 		declare -p | grep "pct_"
		 		ldcDisplay
			  }

			break
		  }

		ldcTestDisplay "${pct_colorText}    Title \"${pc_column[$pct_index]}\"${ldcclr_NoColor}"
		ldcDisplayTs ""

		# =========================================================================

		ldcTestDisplay "${pct_colorText}Checking for proper column start:${ldcclr_NoColor}"
		ldcDisplayTs ""

		testExpectedResults ${pct_colStart[$pct_index]} ${pc_colstart[$pct_index]} 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=3

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
		 		declare -p | grep "pc_"
		 		declare -p | grep "pct_"
		 		ldcDisplay
			  }

			break
		  }

		ldcTestDisplay "${pct_colorText}    Column Start: \"${pc_colstart[$pct_index]}\"${ldcclr_NoColor}"
		ldcDisplayTs ""

		# =========================================================================

		ldcTestDisplay "${pct_colorText}Checking for valid title length:${ldcclr_NoColor}"
		ldcDisplayTs ""

		testExpectedResults ${pct_fieldlen[$pct_index]} ${pc_collen[$pct_index]} 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=4

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
		 		declare -p | grep "pc_"
		 		declare -p | grep "pct_"
		 		ldcDisplay
			  }

			break
		  }

		ldcTestDisplay "${pct_colorText}    Title Length: \"${pc_collen[$pct_index]}\"${ldcclr_NoColor}"
		ldcDisplayTs ""

		# =========================================================================

		break
	done
	
	# =============================================================================

	ldcTestBlock "End ${pct_colorBlock}pctMap${ldcclr_NoColor} tests. ${pct_colorText}(${pct_results})${ldcclr_NoColor}"

	[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
	 {
	 	declare -p | grep "pc_"
	 	declare -p | grep "pct_"
	 	ldcDisplay
	 }

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${pct_results}
}

# =========================================================================================
#
#	pctMap
#
#		test:	locates the name of the field following the specified field in cstatus[0] 
#				returns the index.
#
#	parameters:
#		none
#
#    Returns:
#        0 = no error
#		 non-zero = error code
#
# =========================================================================================
function pctMap()
{
	(( td_indent+=2 ))

	ldcTestBlock "Start ${pct_colorBlock}pctMap${ldcclr_NoColor} tests"
	pct_results=0	

	pct_fieldindex=0

	while [ true ]
	do
		ldcTestDisplay "Checking pcMap"
		ldcDisplayTs ""

		bashLineLocation ; pcMap "${pct_substr}" "${pct_header}"
		[[ $? -eq 0 ]] || 
		 {
			pct_results=$?
			if [ $pct_results -gt 1 ]
			then
				ldcTestDisplay "${pct_colorError}pcMap failed (${pct_results}).${ldcclr_NoColor}${pct_colorText}${bashLTriplet} ($err)${ldcclr_NoColor}"
				break
			fi
			
			pct_results=0
		 }

		ldcTestDisplay "    pcMap OK"
		ldcDisplayTs ""

		# =========================================================================
		
		ldcTestDisplay "Checking pc_index for proper number of elements"
		ldcDisplayTs ""

		testExpectedResults ${#pct_fieldtitle[*]} ${pc_index} 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=4

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }
		 
		ldcTestDisplay "    Number of elements (pc_index) OK"
		ldcDisplayTs ""

		# =========================================================================
		
		ldcTestDisplay "Checking column arrays (by index) for proper values"
		ldcDisplayTs ""

		pct_index=0
		while [ ${pct_index} -lt ${#pct_fieldtitle[*]} ]
		do
			pctMapCheck
			[[ $? -eq 0 ]] || pct_results=$?
			
			[[ ${pct_results} -eq 0 ]] || break
			
			(( pct_index++ ))
		done

		# =================================================================================

		break
	done

	ldcTestBlock "End ${pct_colorBlock}pctMap${ldcclr_NoColor} tests. ${pct_colorText}(${pct_results})${ldcclr_NoColor}"

	[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
	 {
	 	declare -p | grep "pc_"
	 	declare -p | grep "pct_"
	 	ldcDisplay
	 }

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${pct_results}
}

# =========================================================================================
#
#	pctMapColumn
#
#	parameters:
#		none
#
#    Returns:
#        0 = no error
#		 non-zero = error code
#
# =========================================================================================
function pctMapColumn()
{
	(( td_indent+=2 ))

	ldcTestBlock "Start ${pct_colorBlock}pctMapColumn${ldcclr_NoColor} tests"
	pct_results=0	

	pct_substr="  "

	# =====================================================================================
		
	pc_headline="${pct_header}"
	(( pc_headlen=${#pct_header}-${#pct_substr} ))

	pc_next=0
	pct_fieldindex=0

	# =====================================================================================
		
	while [ ${pct_results} -eq 0 ]
	do
		ldcTestBlock "Processing ${pct_colorBlock}Field Index ${pct_fieldindex}${ldcclr_NoColor} tests"

		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pc_next ($pc_next) < length of pct_header (${#pct_header})"

		[[ $pc_next -lt ${#pct_header} ]] || 
		 {
		 	[[ $pc_next -eq ${#pct_header} ]] && break

		 	pct_results=1
		 	[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }

		ldcTestDisplay "    pc_next OK"

		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pc_next ($pc_next) < length of pct_header (${pc_headlen})"

		[[ ${pc_next} -ge ${pc_headlen} ]] && 
		 {
		 	ldcTestDisplay "End-of-string detected"
			break   # end-of-string
		 }

		ldcTestDisplay "    pc_next OK"

		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pcMapNext"
		ldcTestDisplay "   pc_next    = \"${pc_next}\""
		ldcTestDisplay "   pc_headlen = \"${pc_headlen}\""
		ldcTestDisplay ""
		ldcTestDisplay "   pct_substr = \"${pct_substr}\""
		ldcTestDisplay "   pct_header = \"${pct_header}\""

		bashLineLocation ; pcMapNext ${pc_next} "${pct_substr}" "${pct_header}"
		[[ $? -eq 0 ]] || 
		 {
		 	local err=$?
			ldcTestDisplay "${pct_colorError}pcMapNext failed.${ldcclr_NoColor}${pct_colorText}${bashLTriplet} ($err)${ldcclr_NoColor}"
			pct_results=3

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }

		ldcTestDisplay "    pcMapNext OK"

		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pc_title"

		testExpectedResults "${pct_fieldtitle[$pct_fieldindex]}" "${pc_title}" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=4

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }
		 
		ldcTestDisplay "    pc_title OK"

		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pc_offset"

		testExpectedResults "${pct_colStart[$pct_fieldindex]}" "${pc_offset}" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=5

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }
		 
		ldcTestDisplay "    pc_offset OK"

		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pc_length"

		testExpectedResults "${pct_fieldlen[$pct_fieldindex]}" "${pc_length}" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=6

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }
		 
		ldcTestDisplay "    pc_length OK"

		# =================================================================================
		
		(( pc_next=${pc_offset}+${pc_length} ))

		(( pct_next=${pct_colStart[$pct_fieldindex]}+${pct_fieldlen[$pct_fieldindex]} ))
		
		# =================================================================================
		
		ldcDisplayTs ""
		ldcTestDisplay "Checking pc_next"

		testExpectedResults "${pct_next}" "${pc_next}" 0
		[[ $? -eq 0 ]] ||
		 {
			ldcDisplayTs ""
			ldcTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${ldcclr_NoColor}"
			pct_results=1

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }

			break
		 }

		ldcTestDisplay "    pc_next OK"

		# =================================================================================
		
		(( pct_fieldindex++ ))

		[[ $pct_results -eq 0 ]] || break
	done

	# =====================================================================================
		
	[[ ${pct_results} -lt 2 ]] ||
	 {
		ldcTestDisplay "${pct_colorError}testExpectedResults failed ${ldcclr_NoColor}${pct_colorText}@ ${bashLTriplet}${ldcclr_NoColor}"
		ldcDisplayTs ""

		[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	ldcDisplay
			 }
 	 }

	ldcTestBlock "End ${pct_colorBlock}pctMapColumn${ldcclr_NoColor} tests. ${pct_colorText}(${pct_results})${ldcclr_NoColor}"

	# =====================================================================================
		
	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${pct_results}
}

# =========================================================================
#
#   pctRun
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function pctRun()
{
	(( td_indent+=2 ))

	ldcTestBlock "Start ${pct_colorBlock}pctRun${ldcclr_NoColor} tests"
	pct_results=0

	local empty=""

	while [ true ]
	do
		ldcTestDivider 1

        # =================================================================
		#
		#	test: column mapping
		#
        # =================================================================
		pctMapColumn
		[[ ${pct_results} -lt 2 ]] || break

        # =================================================================
		#
		#	test: build title and info in map arrays
		#
        # =================================================================
		pctMap
		[[ ${pct_results} -eq 0 ]] || break

        # =================================================================
		#
		#	test: List titles and info in map arrays
		#		(requires pcMap first)
		#
        # =================================================================
		pctMapList
		[[ ${pct_results} -eq 0 ]] || break

        # =================================================================
		#
		#	test: Lookup titles and info in map arrays
		#		(requires pcMap first)
		#
        # =================================================================
		pctMapFind
		[[ ${pct_results} -eq 0 ]] || break

        # =================================================================

		[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
		 {
		 	declare -p | grep "pc_"
		 	declare -p | grep "pct_"
		 	ldcDisplay
		 }

		ldcTestDisplay "  ${pct_colorText}Successful processing completed.${ldcclr_NoColor}"
		
		break
	done

	[[ ${pct_results} -eq 0 ]] || ldcTestDisplay "  ${pct_colorError}Processing failed, ${ldcclr_NoColor}${pct_colorText}Error: (${pct_results})${ldcclr_NoColor}"
	ldcDisplayTs

	ldcTestBlock "End ${pct_colorBlock}pctRun${ldcclr_NoColor} tests. ${pct_colorText}(${pct_results})${ldcclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${pct_results}
}

# =========================================================================
# =========================================================================
#
#   main script starts here
#
# =========================================================================
# =========================================================================

ldccli_optQuiet=0
ldccli_optDebug=0

pct_results=0
td_indent=18

ldcCliToDcl

# =========================================================================

scrFileName

ldcTestDivider 1 ${pct_colorStartDivider}
  ldcTestDisplay "${pct_colorText}${scr_Name} v ${pct_version}${ldcclr_NoColor}" 1
ldcTestDivider 1 ${pct_colorStartDivider}

# =========================================================================

td_indent=1
ldcTestBlock "${pct_colorText}Start tests${ldcclr_NoColor}"

pctRun
[[ $? -eq 0 ]] || pct_results=$?

ldcTestDivider 1
ldcTestBlock "${pct_colorText}End tests, result: (${pct_results})${ldcclr_NoColor}"
ldcDisplay

# =========================================================================

exit ${pct_results}

