#!/bin/bash
# =========================================================================
# =========================================================================
#
#	lms-utilities
#	  Archive lms library files in lms-utilities tarball (archive)
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.1
# @copyright © 2018, 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/lms-utilities
# @subpackage lms-utilities
#
# =========================================================================
#
#	Copyright © 2018, 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/lms-utilities.
#
#   ewsdocker/lms-utilities is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/lms-utilities is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/lms-utilities.  If not, see 
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
#   LMSUTIL_NAME = name of the tarball (w/o version)
#   LMSUTIL_DIR  = name of the target directory (defaults to "usr")
#   LMSUTIL_DEST = path to the archive storage directory
#
# =========================================================================

. /${LMSUTIL_NAME}/usr/local/lib/lms/lmsArchiveUtilities.lib
. /${LMSUTIL_NAME}/usr/local/lib/lms/lmsBashVars.lib
. /${LMSUTIL_NAME}/usr/local/lib/lms/lmsDeclare.lib
. /${LMSUTIL_NAME}/usr/local/lib/lms/lmsDisplay.lib
. /${LMSUTIL_NAME}/usr/local/lib/lms/lmsStr.lib

# =========================================================================
#
#	archiveLibrary
#		Add libraries to the archive using global vars
#
#	parameters:
#		none
#
#    Result:
#        0 = no error
#        non-zero = archive call number that failed
#
# =========================================================================
function archiveLibrary()
{
	local    arslt=0
	local	 opwd=${PWD}

	cd "/${LMSUTIL_NAME}"

	while [ true ]
	do
		lmsArchive "${LMSUTIL_TARNAME}" "${LMSUTIL_NAME}" "${LMSUTIL_USR_DIR}" "${LMSUTIL_DEST}" "0"
		[[ $? -eq 0 ]] || 
		 {
			arslt=1
			break
		 }
	
		lmsArchive "${LMSUTIL_TARNAME}" "${LMSUTIL_NAME}" "${LMSUTIL_ETC_DIR}" "${LMSUTIL_DEST}" "1"
		[[ $? -eq 0 ]] || 
		 {
			arslt=2
			break
		 }

		cd "${LMSUTIL_DEST}"
		[[ $? -eq 0 ]] || 
		 {
			arslt=3
			break
		 }

		lmsArchiveGzip "${LMSUTIL_TARNAME}"
		[[ $? -eq 0 ]] || arslt=4

		break
	done

	cd "${opwd}"

	return $?
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

lmsDisplay "   Creating archive ${LMSUTIL_TARBALL}"
lmsDisplay ""

lmsCRm=$( rm ${LMSUTIL_DEST}/${LMSUTIL_TARBALL}* >/dev/null 2>/dev/null )

archiveLibrary ; lmsResult=$?
[[ ${lmsResult} -eq 0 ]] && lmsMessage="   Created archive: ${LMSUTIL_TARBALL}" || lmsMessage="lmsArchiveUtilities failed: ${lmsResult}" 

lmsDisplay ""
lmsDisplay ${lmsMessage}

lmsDisplay ""
lmsDisplay "*************************************************************"
lmsDisplay ""

# =========================================================================

exit ${lmsResult}
