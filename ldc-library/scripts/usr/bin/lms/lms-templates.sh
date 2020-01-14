#!/bin/bash
# =========================================================================
# =========================================================================
#
#	lms-templates
#	  Archive lms library files in lms-templates tarball (archive)
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.2
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/lms-templates
# @subpackage lms-templates
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/lms-templates.
#
#   ewsdocker/lms-templates is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/lms-templates is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/lms-templates.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	Global declarations
#
# =========================================================================

declare lmsResult=0
declare lmsMessage=""

# =========================================================================
#
#   LMSLIB_NAME = name of the tarball (w/o version)
#	LMSLIB_VERS = tarball version
#   LMSLIB_TEMPL  = name of the target directory (defaults to "usr")
#   LMSLIB_DEST = path to the archive storage directory
#
# =========================================================================

. /usr/local/lib/lms/lmsArchiveUtilities.lib
. /usr/local/lib/lms/lmsBashVars.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsStr.lib

# =========================================================================
#
#	lmsCArchive
#		Create the archive of the lms-templates using global variables
#
#	parameters:
#		AName = archive name
#		AVers = archive version
#		ATDir = usr directory
#		AUDes = destination folde
#    Result:
#        0 = no error
#        non-zero = archive call number that failed
#
# =========================================================================
function lmsCArchive()
{
	local    AName=${1}
	local    AVers=${2}
	local    ATDir=${3}
	local    AUDes=${4}

	cd "/${ATDir}"

	while [ true ]
	do
		lmsArchive ${AName}-${AVers} ${ATDir} ${AUDes} 1
		[[ $? -eq 0 ]] || return 1
	
		break
	done

	cd "/root"

	return 0
}

# =========================================================================
# =========================================================================
#
#	Start of application
#
# =========================================================================
# =========================================================================

lmsDisplay ""
lmsDisplay "*************************************************************" 
lmsDisplay ""

lmsDisplay "   Creating archive \"${LMSTEMPL_PKG}\""
lmsDisplay ""

	lmsCRm=$( rm "${LMSLIB_DEST}/${LMSLIB_NAME}-${LMSLIB_VERS}.* >/dev/null 2>/dev/null" )

	lmsCArchive "${LMSLIB_NAME}" "${LMSLIB_VERS}" "${LMSLIB_TEMPL}" "${LMSLIB_DEST}"
	lmsResult=$?

[[ ${lmsResult} -eq 0 ]] && lmsMessage="   Created archive: ${LMSTEMPL_PKG}" || lmsMessage="lmsArchiveUtilities failed: ${result}" 

lmsDisplay ${lmsMessage}

lmsDisplay ""
lmsDisplay "*************************************************************"
lmsDisplay ""

# =========================================================================

exit ${lmsResult}
