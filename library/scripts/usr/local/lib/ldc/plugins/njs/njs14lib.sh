
# ===================================================================================
# ===================================================================================
#
#	njs14lib.sh
#       NodeJS plugin script.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-framework
# @subpackage plugins/nodejs
#
# ===================================================================================
#
#	Copyright © 2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-framework.
#
#   ewsdocker/ldc-framework is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-framework is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-framework.  If not, see <http://www.gnu.org/licenses/>.
#
# ===================================================================================
# ===================================================================================

# ===================================================================================
#
#	njs14InstallRepository - Install the NodeJS Repository into the APT Repository.
#
#   Enter:
#       njsUrl = network address of NodeJs version to be installed (NJS_URL)
#       njsName = name of the NodeJs version to be installed (NJS_NAME)
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function njs14InstallRepository()
{
	local njsUrl="${1}"
	local njsName="${2}"

    wget ${njsUrl}
    [[ $? -eq 0 ]] || return $?

    chmod +x ${njsName}
    [[ $? -eq 0 ]] || return $?

    ./${njsName}
    [[ $? -eq 0 ]] || return $?

    rm ./${njsName}

	return 0
}

# ===================================================================================
#
#	njs14InstallJs - The Repository is already install in APT,
#                    install the selected NodeJS version and support packages.
#
#   Enter:
#       updateRepository = non-empty string causes update prior to install.
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function njs14InstallJs()
{
    pkgAddItemList "libwebkit2gtk-4.0.37 build-essential dpkg-dev libdpkg-perl nodejs"
	return $?
}

# ===================================================================================
#
#	njsExecute - Install the selected NodeJS version and support packages.
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function njs14Execute()
{
	[[ ${pkgListValid} -eq 1 ]] || return 1

    pkgExecute
    [[ $? -eq 0 ]] || return 2

	return 0
}

# ===================================================================================
#
#	njsInstall - Install the specified NodeJS repository into the 
#                APT Package Repository and the NodeJS version.
#
#   Enter:
#       njsUrl = network address of NodeJs version to be installed (NJS_URL)
#       njsName = name of the NodeJs version to be installed (NJS_NAME)
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function njs14Install()
{
    local njs_url=${NJS_URL:-""}
    local njs_name=${NJS_NAME:-""}

	[[ -z "${njs_url}" || -z "${njs_name}" ]] && return 1

    njs14InstallRepository ${njs_url} ${njs_name} 
    [[ $? -eq 0 ]] || return $?

    njs14InstallJs 
    [[ $? -eq 0 ]] || return $?

    njs14Execute 
    [[ $? -eq 0 ]] || return $?

    return $?
}

# =========================================================================
#
#   installNjs14 - install NodeJS
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# =========================================================================
function installNjs14()
{
    njs14Install ${NJS_URL} ${NJS_NAME}
    [[ $? -eq 0 ]] || 
     {
        echo "njs14Install failed to install ${NJS_NAME} in installNjs14 ($?)"
        return 1
     }

    return 0
}

