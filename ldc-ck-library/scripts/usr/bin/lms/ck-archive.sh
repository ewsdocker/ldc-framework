#!/bin/bash
# =========================================================================
# =========================================================================
#
#	ck-archive
#	  Archive CKaptain library files in ck-archive tarball (archive)
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-ckaptain
# @subpackage ck-archive
#
# =========================================================================
#
#	Copyright © 2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-ckaptain.
#
#   ewsdocker/ldc-ckaptain is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-ckaptain is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-ckaptain.  If not, see 
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
#   CKL_NAME = name of the tarball (w/o version)
#   CKL_DIR  = name of the target directory (defaults to "usr")
#   CKL_DEST = path to the archive storage directory
#
# =========================================================================

. /usr/local/lib/lms/lmsArchiveUtilities.lib
. /usr/local/lib/lms/lmsBashVars.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsStr.lib

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

	cd "/${CKL_NAME}" ; arslt=$?

	while [ ${arslt} -eq 0 ]
	do
		lmsArchive "${CKL_TARNAME}" "${CKL_NAME}" "${CKL_DIR}" "${CKL_DEST}" "1" ; arslt=$?
		[[ ${arslt} -eq 0 ]] || break

		cd "${CKL_DEST}" ; arslt=$?
		[[ ${arslt} -eq 0 ]] || break

		lmsArchiveGzip "${CKL_TARNAME}" ; arslt=$?

		break
	done

	cd "${opwd}"

	return ${arslt}
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

lmsDisplay "   Creating archive ${CKL_TARBALL}"
lmsDisplay ""

lmsCRm=$( rm ${CKL_DEST}/${CKL_TARBALL}* >/dev/null 2>/dev/null )

archiveLibrary ; lmsResult=$?
lmsMessage="   Created archive: ${CKL_TARBALL}"
[[ ${lmsResult} -eq 0 ]] || lmsMessage="lmsArchiveUtilities failed." 

lmsDisplay ""
lmsDisplay ${lmsMessage}

lmsDisplay ""
lmsDisplay "*************************************************************"
lmsDisplay ""

# =========================================================================

exit ${lmsResult}
