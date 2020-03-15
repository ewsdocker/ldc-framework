#!/bin/bash
# =========================================================================
# =========================================================================
#
#	ck-stack
#	  Dummy stack build - must customize and insert at appropriate location
#							in the build stack.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-ckaptain
# @subpackage ck-stack
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

# *****************************************************************************
#
#	commandExists
#
#		check if the given external command has been installed
#
#	parameters:
#		cmnd = command to check for
#
#	returns:
#		0 = found
#       1 = not present
#
# *****************************************************************************
function commandExists()
{
	local cmnd=${1}

	echo "Checking if \"$cmnd\" is installed."

	type ${cmnd} >/dev/null 2>&1
	[[ $? -eq 0 ]] || return 1

	return 0
}

# =========================================================================
#
#	installPackage
#		download & install requested package
#
#   Enter:
#		instPkg = "package" name downloaded - what you are going to extract from
#		instUrl = server to download the pkg from
#
# =========================================================================
function installPackage()
{
	local instPkg="${1}"
	local instUrl="${2}"

    wget "${instUrl}"
    [[ $? -eq 0 ]] || return $?

    dpkg -i "${instPkg}"
    [[ $? -eq 0 ]] || return $?

    rm "${instPkg}"

    return 0
}

# =========================================================================
function qtExists()
{
	echo "Checking for Qt4 installed."

    [[ -e "/usr/share/doc/libqtcore4" ]] ||
     {
	    echo "Required Qt4 was found... installing Qt4"

	    /lmstmp/ck-qt.sh
	    [[ $? -eq 0 ]] || return 1
     }

	return 0
}

# =========================================================================

qtExists "/usr/share/doc/libqtcore4"
[[ $? -eq 0 ]] || exit $?

# =========================================================================

commandExists "kaptain"
[[ $? -eq 0 ]] ||
 {
	echo "Kaptain is not installed... "
	echo "Downloading and installing kaptain"

	installPackage "${KAPT_PKG}" "${KAPT_URL}"
	[[ $? -eq 0 ]] || exit 2

    chmod +x /usr/bin/kaptain
 }

# =========================================================================

update-locale LANG=C.UTF-8 LC_MESSAGES=POSIX

rm -Rf /ckaptain

# =========================================================================

exit 0
