
# ===================================================================================
# ===================================================================================
#
#	njs.sh
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
#	njsInstallRepository - Install the NodeJS Repository into the APT Repository.
#
#   Enter:
#       njsUrl = network address of NodeJs version to be installed (NJS_URL)
#       njsName = name of the NodeJs version to be installed (NJS_NAME)
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function njsInstallRepository()
{
	local njsUrl="${1}"
	local njsName="${2}"

    echo
    echo "*********** Installing NodeJS ${njsUrl}/${njsName} ***********"
    echo

    while (true)
    do
        wget ${njsUrl}
        [[ $? -eq 0 ]] || break

        chmod +x ${njsName}
        [[ $? -eq 0 ]] || break

        ./${njsName}
        [[ $? -eq 0 ]] || break

        rm ./${njsName}

        pkgUpdate

        pkgExecute
        [[ $? -eq 0 ]] || break

    done

	return $?
}

# ===================================================================================
#
#	njsInstallJs - The Repository is already install in APT,
#                  install the selected NodeJS version and support packages.
#
#   Enter:
#       none
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function njsInstallJs()
{
    echo
    echo "*********** Installing NodeJS ***********"
    echo

    pkgUpdate

    pkgExecute
    [[ $? -eq 0 ]] || return $?

    pkgInstall

	pkgAddItem "build-essential"
	pkgAddItem "dpkg-dev"
	pkgAddItem "libdpkg-perl"
	pkgAddItem "nodejs"

    pkgExecute
    [[ $? -eq 0 ]] || return $?

    apt-get clean all
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
function njsInstall()
{
    local njs_url="${NJS_URL:-""}
    local njs_name="${NJS_NAME:-""}

	[[ -z "${njs_url}" || -z "${njs_name}" ]] && return 1

    njsInstallRepository ${njs_url} ${njs_name}
    [[ $? -eq 0 ]] || return $?

    njsInstallJs || return $?

    return $?
}
