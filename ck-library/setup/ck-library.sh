#!/bin/bash
# =========================================================================
# =========================================================================
#
#	ckaptain
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-ckaptain
# @subpackage ckaptain
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

	type ${cmnd} >/dev/null 2>&1
	[[ $? -eq 0 ]] || return 1

	return 0
}

# =========================================================================
#
#	qtExists
#		Install Qt4 if it is not already installed.
#
#	Enter:
#		name = name of Qt file to check for, default = libqtcore4
#
#	Exit:
#		0 = exists
#		1 = error
#
# =========================================================================
function qtExists()
{
	fName=${1:-"/usr/share/doc/libqtcore4"}

    [[ -e "${1}" ]] ||
     {
	    echo "Installing Qt4"

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
	echo "Installing kaptain"

	installPackage "${KAPT_PKG}" "${KAPT_URL}"
	[[ $? -eq 0 ]] || exit 2

    chmod +x /usr/bin/kaptain
 }

# =========================================================================

update-locale LANG=C.UTF-8 LC_MESSAGES=POSIX

ln -s /usr/bin/lms/ck-archive.sh /usr/bin/ck-archive
chmod +x /usr/bin/lms/ck-archive.sh

echo "ck-library completed"

# =========================================================================

exit 0
