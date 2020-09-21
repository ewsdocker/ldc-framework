
# ===================================================================================
# ===================================================================================
#
#	npmlib.sh
#       Install bash-server from npm repository.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-framework
# @subpackage plugins/npm
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
#	npmInstall - Install the bash-language-server from the NPM Repository.
#
#   Requirements:
#		NodeJS v 14, or similar, software pre-installed.
#   Enter:
#       npmPackage = name of the package to install
#   Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function npmInstall()
{
	local npmPackage=${1:-""}

	[[ -z "${npmPackage}" ]] && return 255

    npm i -g "${npmPackage}"

    return $?
}


