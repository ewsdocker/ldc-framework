#!/bin/bash
# =========================================================================
# =========================================================================
#
#	lmsDQueryTests.sh
#		Test performance of lmsDockerQuery.lib.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-utilities
# @subpackage lmsDQueryTests.sh
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

declare    dqt_version="0.1.0"

declare -i dqt_optDev=1
declare -i dqt_optDcl=1

declare    dqt_libRoot="../ldc-core"
[[ ${dqt_optDev} -eq 0 ]] || dqt_libRoot="../ldc-utilities"

# =========================================================================
#
#		Load Libraries
#
# =========================================================================

. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsTestUtilities.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsBashVars.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsconCli.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsColorDef.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsDeclare.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsDisplay.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsDockerQuery.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsParseColumns.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsScriptName.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsStr.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsTestUtilities.lib
. ${dqt_libRoot}/scripts/usr/local/lib/lms/lmsUtilities.lib

#. ./scripts/usr/local/lib/lms/lmsDockerQuery.lib

# =========================================================================
# =========================================================================
#
#	Global declarations
#
# =========================================================================
# =========================================================================

	# =====================================================================
	#
	#		Load dockerQueryTests configuration
	#
	# =====================================================================

	. ${dqt_libRoot}/scripts/usr/local/share/lms/config/dockerQueryTests.cfg

	# =====================================================================

	declare -i dqt_results=0

	declare -i dqt_imageindex=0
	declare    dqt_imageName=""
	declare    dqt_containerName=""

	declare    ANCHOR="${dqt_colorText}dq"

# =========================================================================
# =========================================================================
#
#	Library functions
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#   dqtFqnToNames 
#		tests: parse dqt_imageName to extract dq_iname, dq_cname and dq_rname
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function dqtFqnToNames()
{
	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtFqnToNames${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}	
	
	bashLineLocation ; dqFqnToNames ${dq_iname}
	[[ $? -eq 0 ]] || 
	 {
		lmsTestDisplay "${dqt_colorError}dqFqnToNames $dqt_imageName failed.${lmsclr_NoColor}${dqt_colorText}${bashLTriplet}${lmsclr_NoColor}"
		dqt_results=${dqerror_names}
		
		[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
	 }

	[[ ${dqt_results} -eq ${dqerror_none} ]] &&
	 {
		(( td_indent+=2 ))
		lmsTestDisplay "${dqt_colorText}dq_iname: ${dq_iname}${lmsclr_NoColor}"
		lmsTestDisplay "${dqt_colorText}dq_cname: ${dq_cname}${lmsclr_NoColor}"
		lmsTestDisplay "${dqt_colorText}dq_rname: ${dq_rname}${lmsclr_NoColor}"
		((  td_indent-=2 ))

		lmsDisplayTs ""

		testExpectedResults "${dq_iname}" "${dq_cname}" 1
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${dqt_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			dqt_results=${dqerror_names}
		
			[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
		 }
	 }

	lmsTestBlock "End ${dqt_colorBlock}dqtFqnToNames${lmsclr_NoColor} tests ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
}

# =========================================================================
#
#   dqtStrSplit
#		tests: check ability of lmsStrSplit to extract
#				the repository and image-name from dqt_imageName
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function dqtStrSplit()
{
	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtStrSplit${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}	
	
	while [ true ]
	do
		bashLineLocation ; lmsStrSplit $dqt_imageName "dq_repo" "dq_iname" "/"
		[[ $? -eq 0 ]] || 
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${dqt_colorError}${bashLTriplet} : lmsStrSplit $dqt_imageName failed.${lmsclr_NoColor}"
			dqt_results=${dqerror_names}
			[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
			break
	 	 }

		testExpectedResults "${dq_iname}" "${empty}" 0
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${dqt_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
			lmsDisplayTs ""
			dqt_results=${dqerror_names}
			[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
			break
		 }
		 
		 break
	done
		 
	lmsTestBlock "End ${dqt_colorBlock}dqtStrSplit${lmsclr_NoColor} tests ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
}

# =========================================================================
#
#   dqtReassignRepo
#		tests: re-assignment of dq_repo into dq_repo and dq_iname if it contains a "/"
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function dqtReassignRepo()
{
	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtReassignRepo${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}	
	
	bashLineLocation ; [[ -z ${dq_iname} ]] && 
	 {
		(( td_indent+=2 ))
	 		lmsTestDisplay "${dqt_colorText}re-assign dq_repo to dq_iname and dqt_defRepo to dq_repo${lmsclr_NoColor}"
		(( td_indent-=2 ))

		lmsDisplayTs 

	 	dq_iname=${dq_repo}
	 	dq_repo=${dqt_defRepo}
	 }

	(( td_indent+=2 ))
	lmsTestDisplay "${dqt_colorText}dqt_defRepo: ${dqt_defRepo}${lmsclr_NoColor}"
	lmsTestDisplay "${dqt_colorText}dq_iname   : ${dq_iname}${lmsclr_NoColor}"
	lmsTestDisplay "${dqt_colorText}dq_repo    : ${dq_repo}${lmsclr_NoColor}"
	(( td_indent-=2 ))

	lmsDisplayTs ""

	testExpectedResults "${dqt_defRepo}" "${dq_repo}" 0
	[[ $? -eq 0 ]] ||
	 {
		lmsDisplayTs ""
		lmsTestDisplay "${dqt_colorError}testExpectedResults failed @ ${bashLTriplet}${lmsclr_NoColor}"
		dqt_results=${dqerror_names}
		[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
	 }

	lmsTestBlock "End ${dqt_colorBlock}dqtReassignRepo${lmsclr_NoColor} tests ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
}

# =========================================================================================
#
#	dqtGetStatus
#
#		test: get the current status of the CONTAINER
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================================
function dqtGetStatus()
{
	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtGetStatus${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}	
	
	bashLineLocation ; dqGetStatus
	dqt_results=${dq_error}	

	[[ $dqt_results -eq 0 ]] ||
	 {
		[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; declare -p | grep "pc_" ; lmsDisplay "${lmsclr_NoColor} "
	 }

	lmsDisplayTs "      ${dqt_colorText}${dq_repo}/${dq_iname} " 0 n
	[[ ${dqt_results} -eq ${dqerror_none} ]] && lmsDisplay "status: $dq_contStatus${lmsclr_NoColor}" || lmsDisplay "${dqt_colorError}is not running${lmsclr_NoColor}."
	lmsDisplayTs

	lmsTestBlock "End ${dqt_colorBlock}dqtGetStatus${lmsclr_NoColor} tests ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
}

# =========================================================================================
#
#   dqtCheckContainer
#
#  		check if the required docker CONTAINER exists.
#
#   parameters:
#		None
#
#	returns:
#      0 = container exists
#      non-zero = container does not exist
#
# =========================================================================================
function dqtCheckContainer()
{
	local -i l_exists=${1:-"0"}
	local -i l_found=0

	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtCheckContainer${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}	
	
	bashLineLocation ; dqCheckContainer
	[[ ${dq_error} -eq ${dqerror_none} ]] && l_found=0 || l_found=1
	
	[[ ${l_found} -eq ${dqerror_no_image} ]] &&
	 {
		lmsDisplayTs ""
		lmsTestDisplay "${dqt_colorError}dqCheckContainer $dq_iname not found. ${lmsclr_NoColor}${dqt_colorText}@ ${bashLTriplet}${lmsclr_NoColor}"
		dqt_results=${dqerror_none}
		[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
		l_found=1
	 }

	[[ ${dqt_results} -eq ${dqerror_none} ]] &&
	 {
	 	testExpectedResults "${l_exists}" "${l_found}" ${l_exists}
		[[ $? -eq 0 ]] ||
		 {
			lmsDisplayTs ""
			lmsTestDisplay "${dqt_colorError}testExpectedResults failed ${lmsclr_NoColor}${dqt_colorText}@ ${bashLTriplet}${lmsclr_NoColor}"
			lmsDisplayTs ""
			dqt_results=${dqerror_no_image}
			[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
		 }
	 }

	lmsTestBlock "End ${dqt_colorBlock}dqtCheckContainer${lmsclr_NoColor} tests ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
}

# =========================================================================================
#
#   dqtCheckImage
#
#  		test: check if the required docker IMAGE exists.
#
#   parameters:
#		l_exists = (optional) 0 = image should exist (default), 1 = image should not exist
#
#	returns:
#      0 = no error
#      non-zero = error number
#
# =========================================================================================
function dqtCheckImage()
{
	local -i l_exists=${1:-"0"}
	local -i l_found=0

	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtCheckImage${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}	
	
	bashLineLocation ; dqCheckImage
	[[ ${dq_error} -eq ${dqerror_none} ]] && l_found=0 || l_found=1
	
	[[ ${dq_error} -eq ${dqerror_no_image} ]] &&
	 {
		lmsTestDisplay "${dqt_colorError}dqCheckImage ${lmsclr_NoColor}${dqt_colorText}$dq_iname${lmsclr_NoColor}${dqt_colorError} not found. ${lmsclr_NoColor}${dqt_colorText}@ ${bashLTriplet}${lmsclr_NoColor}"
		lmsDisplayTs
		dqt_results=${dqerror_none}
		[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
		l_found=1
	 }

	[[ ${dqt_results} -eq ${dqerror_none} ]] &&
	 {
	 	testExpectedResults "${l_exists}" "${l_found}" ${l_exists}
		[[ $? -eq 0 ]] ||
		 {
			lmsTestDisplay "${dqt_colorError}testExpectedResults failed ${lmsclr_NoColor}${dqt_colorText}@ ${bashLTriplet}${lmsclr_NoColor}"
			lmsDisplayTs ""
			dqt_results=${dqerror_no_image}
			[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "
		 }
	 }

	lmsTestBlock "End ${dqt_colorBlock}dqtCheckImage${lmsclr_NoColor} tests. ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
}

# =========================================================================
#
#   dqtRun
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = error number
#
# =========================================================================
function dqtRun()
{
	(( td_indent+=2 ))

	lmsTestBlock "Start ${dqt_colorBlock}dqtRun${lmsclr_NoColor} tests"
	dqt_results=${dqerror_none}

	local empty=""

	dqt_imageindex=0
	while [ $dqt_imageindex -lt ${#dqt_imagelist[*]} ]
	do
		dqInitialize

		dqt_imageName="${dqt_imagelist[$dqt_imageindex]}"

		dq_cname=${dqt_images[$dqt_imageName]}

		lmsTestDivider 1

		lmsTestDisplay "  ${dqt_colorText}Processing $dqt_imageName${lmsclr_NoColor}"
		lmsDisplayTs

        # =================================================================
		#
		#	tests: extract repository and image-name from dqt_imageName
		#
        # =================================================================
		dqtStrSplit
		dqt_results=$?
		[[ ${dqt_results} -eq 0 ]] || break

        # =================================================================
		#
		#	tests: re-assignment of dq_repo into dq_repo and dq_iname if it contains a "/"
		#
        # =================================================================
		dqtReassignRepo
		dqt_results=$?
		[[ ${dqt_results} -eq 0 ]] || break

        # =================================================================
		#
		#	tests: parse dqt_imageName to extract dq_iname, dq_cname and dq_rname
		#
        # =================================================================
		dqtFqnToNames
		dqt_results=$?
		[[ ${dqt_results} -eq 0 ]] || break

        # =================================================================
		#
		#	test: check if the required docker IMAGE exists.
		#
        # =================================================================
		dqtCheckImage
		dqt_results=$?
		[[ ${dqt_results} -eq 0 ]] || break

        # =================================================================
		#
		#	test: get the current status of the CONTAINER
		#
        # =================================================================
		dqtGetStatus
		dqt_results=$?
		[[ ${dqt_results} -eq 0 ]] || break

        # =================================================================
		#
		#	test: 
		#
        # =================================================================

        # =================================================================
		#
		#	End of tests 
		#
        # =================================================================
		[[ ${dqt_optDev} -eq 1 && ${dqt_optDcl} -eq 1 ]] && declare -p | grep "dq" ; lmsDisplay "${lmsclr_NoColor} "

		lmsTestDisplay "  ${dqt_colorSuccessful}Successful processing of ${lmsclr_NoColor}${dqt_colorText}$dqt_imageName${lmsclr_NoColor}${dqt_colorSuccessful} completed.${lmsclr_NoColor}"

		(( dqt_imageindex++ ))
	done

	[[ ${dqt_results} -eq 0 ]] || lmsTestDisplay "  ${dqt_colorError}Processing of ${lmsclr_NoColor}${dqt_colorText}$dq_iname${lmsclr_NoColor}${dqt_colorError} failed, ${lmsclr_NoColor}${dqt_colorText}Error: (${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"
	lmsDisplayTs

	lmsTestBlock "End ${dqt_colorBlock}dqtRun${lmsclr_NoColor} tests. ${dqt_colorText}(${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"

	[[ ${td_indent} -ge 2 ]] && (( td_indent-=2 ))
	return ${dqt_results}
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

dqt_results=0
td_indent=18

lmsCliToDcl

# =========================================================================

scrFileName

lmsTestDivider 1 ${dqt_colorStartDivider}
  lmsTestDisplay "${dqt_colorText}${scr_Name} v ${dqt_version}${lmsclr_NoColor}" 1
lmsTestDivider 1 ${dqt_colorStartDivider}

# =========================================================================

td_indent=1
lmsTestBlock "${dqt_colorText}Start tests${lmsclr_NoColor}"

dqtRun
[[ $? -eq ${dqerror_none} ]] || dqt_results=$?

lmsTestDivider 1
lmsTestBlock "${dqt_colorText}End tests, result: (${dq_error}) ${dqerror_message[${dq_error}]}${lmsclr_NoColor}"
lmsDisplay

# =========================================================================

exit ${dqt_results}

