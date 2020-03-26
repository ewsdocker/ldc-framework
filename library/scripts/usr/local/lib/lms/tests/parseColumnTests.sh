#!/bin/bash
# =========================================================================
# =========================================================================
#
#	lmsParseColumnTests.sh
#		Test performance of lmsParseColumn.lib.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-utilities
# @subpackage lmsParseColumnTests.sh
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
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

declare    pct_version="0.1.0"

declare -i pct_optDev=1
declare -i pct_optDcl=0

declare    pct_libRoot="."
[[ ${pct_optDev} -eq 0 ]] || pct_libRoot="../ldc-utilities"

# =========================================================================
#
#		Load Libraries
#
# =========================================================================

. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsTestUtilities.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsBashVars.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsconCli.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsColorDef.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsDeclare.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsDisplay.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsDockerQuery.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsParseColumns.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsScriptName.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsStr.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsTestUtilities.lib
. ${pct_libRoot}/scripts/usr/local/lib/lms/lmsUtilities.lib

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

. ${pct_libRoot}/scripts/usr/local/share/lms/config/parseColumnTests.cfg

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
#
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

	testExpectedResults $pct_expected $pct_found "$pc_match"
	[[ $? -eq 0 ]] ||
	 {
		lmsDisplayTs ""
		lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
		pct_results=1

		[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
		 {
 			declare -p | grep "pc_"
 			declare -p | grep "pct_"
 			lmsDisplay
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
	lmsTestDisplay "${pct_colorBlock}Map Find${lmsclr_NoColor}"
	lmsDisplayTs ""

	(( td_indent+=2 ))

	pct_results=0

	while [ true ]
	do

		# =========================================================================

		lmsTestDisplay "${pct_colorText}Checking for Column Names:${lmsclr_NoColor}"
		lmsDisplayTs ""

		pct_index=0
		while [ $pct_index -lt ${#pc_column[*]} ]
		do
			bashLineLocation ; pctMapLookup "0" "0" $pct_index 

			lmsTestDisplay "${pct_colorText} pct_index = ${pct_index}: \"${pc_column[$pct_index]}\"${lmsclr_NoColor}"
			lmsDisplayTs ""
			
			(( pct_index++ ))
		done

		[[ $pct_results -eq 0 ]] || break

		# =========================================================================

		lmsTestDisplay "${pct_colorText}Checking for Bad Column Name${lmsclr_NoColor}"
		lmsDisplayTs ""

		pct_index=${#pc_column[*]}
		bashLineLocation ; pctMapLookup "1" "0" $pct_index

		[[ $pct_results -eq 0 ]] || break
		lmsTestDisplay "${pct_colorText} pct_index = ${pct_index}: \"${pct_fieldtitle[$pct_index]}\"${lmsclr_NoColor}"

		# =========================================================================

		break
	done
	
	lmsDisplayTs ""

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
	lmsTestDisplay "${pct_colorBlock}Map Listing  [ TITLE = START COLUMN : TITLE WIDTH : DATA WIDTH ]${lmsclr_NoColor}"
	lmsDisplayTs ""

	(( td_indent+=2 ))

	pct_index=0
	while [ ${pct_index} -lt ${#pc_column[*]} ]
	do
		lmsTestDisplay "${pct_colorText}${pc_column[$pct_index]} = ${pc_colstart[$pct_index]} : ${pc_collen[$pct_index]} : ${pc_coldata[$pct_index]}${lmsclr_NoColor}"
		(( pct_index++ ))
	done
	
	lmsDisplayTs ""

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
	lmsTestDisplay "${pct_colorBlock}Checking corresponding values for index: $pct_index${lmsclr_NoColor}"
	lmsDisplayTs ""

	(( td_indent+=2 ))

	while [ true ]
	do
		lmsTestDisplay "${pct_colorText}Checking for proper title:${lmsclr_NoColor}"
		lmsDisplayTs ""

		testExpectedResults "${pct_fieldtitle[$pct_index]}" "${pc_column[$pct_index]}" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=2

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
		 		declare -p | grep "pc_"
		 		declare -p | grep "pct_"
		 		lmsDisplay
			  }

			break
		  }

		lmsTestDisplay "${pct_colorText}    Title \"${pc_column[$pct_index]}\"${lmsclr_NoColor}"
		lmsDisplayTs ""

		# =========================================================================

		lmsTestDisplay "${pct_colorText}Checking for proper column start:${lmsclr_NoColor}"
		lmsDisplayTs ""

		testExpectedResults ${pct_colStart[$pct_index]} ${pc_colstart[$pct_index]} 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=3

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
		 		declare -p | grep "pc_"
		 		declare -p | grep "pct_"
		 		lmsDisplay
			  }

			break
		  }

		lmsTestDisplay "${pct_colorText}    Column Start: \"${pc_colstart[$pct_index]}\"${lmsclr_NoColor}"
		lmsDisplayTs ""

		# =========================================================================

		lmsTestDisplay "${pct_colorText}Checking for valid title length:${lmsclr_NoColor}"
		lmsDisplayTs ""

		testExpectedResults ${pct_fieldlen[$pct_index]} ${pc_collen[$pct_index]} 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=4

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
		 		declare -p | grep "pc_"
		 		declare -p | grep "pct_"
		 		lmsDisplay
			  }

			break
		  }

		lmsTestDisplay "${pct_colorText}    Title Length: \"${pc_collen[$pct_index]}\"${lmsclr_NoColor}"
		lmsDisplayTs ""

		# =========================================================================

		break
	done
	
	# =============================================================================

	lmsTestBlock "End ${pct_colorBlock}pctMap${lmsclr_NoColor} tests. ${pct_colorText}(${pct_results})${lmsclr_NoColor}"

	[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
	 {
	 	declare -p | grep "pc_"
	 	declare -p | grep "pct_"
	 	lmsDisplay
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

	lmsTestBlock "Start ${pct_colorBlock}pctMap${lmsclr_NoColor} tests"
	pct_results=0	

	pct_fieldindex=0

	while [ true ]
	do
		lmsTestDisplay "Checking pcMap"
		lmsDisplayTs ""

		bashLineLocation ; pcMap "${pct_substr}" "${pct_header}"
		[[ $? -eq 0 ]] || 
		 {
			pct_results=$?
			if [ $pct_results -gt 1 ]
			then
				lmsTestDisplay "${pct_colorError}pcMap failed (${pct_results}).${lmsclr_NoColor}${pct_colorText}${bashLTriplet} ($err)${lmsclr_NoColor}"
				break
			fi
			
			pct_results=0
		 }

		lmsTestDisplay "    pcMap OK"
		lmsDisplayTs ""

		# =========================================================================
		
		lmsTestDisplay "Checking pc_index for proper number of elements"
		lmsDisplayTs ""

		testExpectedResults ${#pct_fieldtitle[*]} ${pc_index} 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=4

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }
		 
		lmsTestDisplay "    Number of elements (pc_index) OK"
		lmsDisplayTs ""

		# =========================================================================
		
		lmsTestDisplay "Checking column arrays (by index) for proper values"
		lmsDisplayTs ""

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

	lmsTestBlock "End ${pct_colorBlock}pctMap${lmsclr_NoColor} tests. ${pct_colorText}(${pct_results})${lmsclr_NoColor}"

	[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
	 {
	 	declare -p | grep "pc_"
	 	declare -p | grep "pct_"
	 	lmsDisplay
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

	lmsTestBlock "Start ${pct_colorBlock}pctMapColumn${lmsclr_NoColor} tests"
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
		lmsTestBlock "Processing ${pct_colorBlock}Field Index ${pct_fieldindex}${lmsclr_NoColor} tests"

		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pc_next ($pc_next) < length of pct_header (${#pct_header})"

		[[ $pc_next -lt ${#pct_header} ]] || 
		 {
		 	[[ $pc_next -eq ${#pct_header} ]] && break

		 	pct_results=1
		 	[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }

		lmsTestDisplay "    pc_next OK"

		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pc_next ($pc_next) < length of pct_header (${pc_headlen})"

		[[ ${pc_next} -ge ${pc_headlen} ]] && 
		 {
		 	lmsTestDisplay "End-of-string detected"
			break   # end-of-string
		 }

		lmsTestDisplay "    pc_next OK"

		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pcMapNext"
		lmsTestDisplay "   pc_next    = \"${pc_next}\""
		lmsTestDisplay "   pc_headlen = \"${pc_headlen}\""
		lmsTestDisplay ""
		lmsTestDisplay "   pct_substr = \"${pct_substr}\""
		lmsTestDisplay "   pct_header = \"${pct_header}\""

		bashLineLocation ; pcMapNext ${pc_next} "${pct_substr}" "${pct_header}"
		[[ $? -eq 0 ]] || 
		 {
		 	local err=$?
			lmsTestDisplay "${pct_colorError}pcMapNext failed.${lmsclr_NoColor}${pct_colorText}${bashLTriplet} ($err)${lmsclr_NoColor}"
			pct_results=3

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }

		lmsTestDisplay "    pcMapNext OK"

		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pc_title"

		testExpectedResults "${pct_fieldtitle[$pct_fieldindex]}" "${pc_title}" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=4

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }
		 
		lmsTestDisplay "    pc_title OK"

		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pc_offset"

		testExpectedResults "${pct_colStart[$pct_fieldindex]}" "${pc_offset}" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=5

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }
		 
		lmsTestDisplay "    pc_offset OK"

		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pc_length"

		testExpectedResults "${pct_fieldlen[$pct_fieldindex]}" "${pc_length}" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=6

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }
		 
		lmsTestDisplay "    pc_length OK"

		# =================================================================================
		
		(( pc_next=${pc_offset}+${pc_length} ))

		(( pct_next=${pct_colStart[$pct_fieldindex]}+${pct_fieldlen[$pct_fieldindex]} ))
		
		# =================================================================================
		
		lmsDisplayTs ""
		lmsTestDisplay "Checking pc_next"

		testExpectedResults "${pct_next}" "${pc_next}" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${pct_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			pct_results=1

			[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }

			break
		 }

		lmsTestDisplay "    pc_next OK"

		# =================================================================================
		
		(( pct_fieldindex++ ))

		[[ $pct_results -eq 0 ]] || break
	done

	# =====================================================================================
		
	[[ ${pct_results} -lt 2 ]] ||
	 {
		lmsTestDisplay "${pct_colorError}testExpectedResults failed ${lmsclr_NoColor}${pct_colorText}@ ${bashLTriplet}${lmsclr_NoColor}"
		lmsDisplayTs ""

		[[ ${pct_optDev} -eq 1 && ${pct_optDcl} -eq 1 ]] && 
			 {
			 	declare -p | grep "pc_"
			 	declare -p | grep "pct_"
			 	lmsDisplay
			 }
 	 }

	lmsTestBlock "End ${pct_colorBlock}pctMapColumn${lmsclr_NoColor} tests. ${pct_colorText}(${pct_results})${lmsclr_NoColor}"

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

	lmsTestBlock "Start ${pct_colorBlock}pctRun${lmsclr_NoColor} tests"
	pct_results=0

	local empty=""

	while [ true ]
	do
		lmsTestDivider 1

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
		 	lmsDisplay
		 }

		lmsTestDisplay "  ${pct_colorText}Successful processing completed.${lmsclr_NoColor}"
		
		break
	done

	[[ ${pct_results} -eq 0 ]] || lmsTestDisplay "  ${pct_colorError}Processing failed, ${lmsclr_NoColor}${pct_colorText}Error: (${pct_results})${lmsclr_NoColor}"
	lmsDisplayTs

	lmsTestBlock "End ${pct_colorBlock}pctRun${lmsclr_NoColor} tests. ${pct_colorText}(${pct_results})${lmsclr_NoColor}"

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

lmscli_optQuiet=0
lmscli_optDebug=0

pct_results=0
td_indent=18

lmsCliToDcl

# =========================================================================

scrFileName

lmsTestDivider 1 ${pct_colorStartDivider}
  lmsTestDisplay "${pct_colorText}${scr_Name} v ${pct_version}${lmsclr_NoColor}" 1
lmsTestDivider 1 ${pct_colorStartDivider}

# =========================================================================

td_indent=1
lmsTestBlock "${pct_colorText}Start tests${lmsclr_NoColor}"

pctRun
[[ $? -eq 0 ]] || pct_results=$?

lmsTestDivider 1
lmsTestBlock "${pct_colorText}End tests, result: (${pct_results})${lmsclr_NoColor}"
lmsDisplay

# =========================================================================

exit ${pct_results}

