#!/bin/bash
# =========================================================================
# =========================================================================
#
#	ldc-library
#	  Archive lms library files in ldc-library tarball (archive)
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.3
# @copyright © 2018-2021. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-library
# @subpackage ldc-library
#
# =========================================================================
#
#	Copyright © 2018-2021. EarthWalk Software
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
#	Global declarations
#
# =========================================================================

declare lmsResult=0
declare lmsMessage=""

# =========================================================================
#
#   LMSU_NAME = name of the tarball (w/o version)
#   LMSU_DIR  = name of the target directory (defaults to "usr")
#   LMSU_DEST = path to the archive storage directory
#
# =========================================================================

. /${LMSU_NAME}/usr/local/lib/lms/lmsArchiveUtilities.lib
. /${LMSU_NAME}/usr/local/lib/lms/lmsBashVars.lib
. /${LMSU_NAME}/usr/local/lib/lms/lmsDeclare.lib
. /${LMSU_NAME}/usr/local/lib/lms/lmsDisplay.lib
. /${LMSU_NAME}/usr/local/lib/lms/lmsStr.lib

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

	cd "/${LMSU_NAME}" ; arslt=$?
	[[ $arslt -eq 0 ]] || return ${arslt}

	while [ ${arslt} -eq 0 ]
	do
		lmsArchive "${LMSU_TARNAME}" "${LMSU_NAME}" "${LMSU_USR_DIR}" "${LMSU_DEST}" "0" ; arslt=$?
		[[ ${arslt} -eq 0 ]] || break

		lmsArchive "${LMSU_TARNAME}" "${LMSU_NAME}" "${LMSU_ETC_DIR}" "${LMSU_DEST}" "1" ; arslt=$?
		[[ ${arslt} -eq 0 ]] || break

		cd "${LMSU_DEST}" ; arslt=$?
		[[ ${arslt} -eq 0 ]] || break

		lmsArchiveGzip "${LMSU_TARNAME}" ; arslt=$?

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

lmsDisplay "   Creating archive ${LMSU_TARBALL}"
lmsDisplay ""

lmsCRm=$( rm ${LMSU_DEST}/${LMSU_TARBALL}* >/dev/null 2>/dev/null )

archiveLibrary ; lmsResult=$?

lmsMessage="   Created archive: ${LMSU_TARBALL}"
[[ ${lmsResult} -eq 0 ]] || lmsMessage="lmsArchiveUtilities failed." 

lmsDisplay ""
lmsDisplay "${lmsMessage}"

# **********************************************************************************

lmsCRm=$( rm -Rf "/opt/dlibrary/${LMSU_VERS}${LMSU_VERSX}" >/dev/null 2>/dev/null )

lmsDisplay ""
lmsDisplay "untar ${LMSU_DEST}/${LMSU_TARGZIP}"
lmsDisplay ""

mkdir -p "/opt/dlibrary/${LMSU_VERS}${LMSU_VERSX}"

oPwd="${PWD}"
cd "/opt/dlibrary/${LMSU_VERS}${LMSU_VERSX}"

tar -xvf "${LMSU_DEST}/${LMSU_TARGZIP}"

cd "${oPwd}"

# **********************************************************************************

lmsDisplay ""
lmsDisplay "*************************************************************"
lmsDisplay ""

