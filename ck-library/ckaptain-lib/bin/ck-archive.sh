#!/bin/bash
# =========================================================================
# =========================================================================
#
#	ck-archive
#	  Archive template library files in ck-archive tarball
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-ckaptain
# @subpackage ck-archive
#
# =========================================================================
#
#	Copyright © 2017-2020. EarthWalk Software
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
# =========================================================================
#
#	Global declarations
#
# =========================================================================
# =========================================================================

declare lmsResult=0

declare local_lib=/usr/local/lib
declare lms_lib=${local_lib}/lms

declare cpath_dir=${local_lib}/ldc-ckaptain
declare cpath_templates=${cpath_dir}/templates

declare cpath_lib=${cpath_dir}/ck-library
declare cpath_vers=${cpath_lib}/${CKLIB_VERS}

declare carchive=${CKLIB_NAME}-${CKLIB_VERS}

# =========================================================================

. ${lms_lib}/lmsDisplay.lib
. ${lms_lib}/lmsArchiveUtilities.lib

# =========================================================================
# =========================================================================
#
#	ck_archive_templates
#
#   Entry:
#      none
#   Returns:
#		0 = no error
#       non-zero = error code
#
# =========================================================================
# =========================================================================
function ck_archive_templates()
{
	lmsDisplay ""
	lmsDisplay "    Creating template library \"${CKLIB_PKG}\""

	# =========================================================================

	mkdir -p ${cpath_vers}
	cp -r ${cpath_templates}/* ${cpath_vers}

	# =========================================================================
	#
	#	create the template library archive directory
	#
	# =========================================================================

	lmsArchive "${carchive}" "${cpath_vers}" "${CKLIB_DEST}" 1 > /dev/null 2>&1
	[[ $? -eq 0 ]] || return $?

	# =========================================================================
	#
	#	produce a listing
	#
	# =========================================================================

	lmsArchiveList ${CKLIB_DEST}/${CKLIB_PKG}
	[[ $? -eq 0 ]] || return $?

	lmsDisplay ""

	# =========================================================================
	#
	#	clean-up and exit with the result from lmsArchiveUtilities 
	#
	# =========================================================================

	rm -Rf ${cpath_lib} > /dev/null 2>&1

	return 0
}

# =========================================================================
# =========================================================================
#
#	Start of application
#
# =========================================================================
# =========================================================================

ck_archive_templates
lmsResult=$?

[[ ${lmsResult} -eq 0 ]] && lmsg="*** Template library: \"${CKLIB_PKG}\" created." || lmsg="lmsArchiveUtilities failed: ${lmsResult}"

lmsDisplay
lmsDisplay "${lmsg}"
lmsDisplay ""

exit ${lmsResult}
