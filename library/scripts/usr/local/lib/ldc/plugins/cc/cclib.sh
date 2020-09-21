
# ===================================================================================
# ===================================================================================
#
#	cclib.sh
#       Install requested cclib from the APT repository.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-applications
# @subpackage eclipse/cclib
#
# ===================================================================================
#
#	Copyright © 2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-applications.
#
#   ewsdocker/ldc-applications is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-applications is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-applications.  If not, see <http://www.gnu.org/licenses/>.
#
# ===================================================================================
# ===================================================================================

# ===================================================================================
#
#    ccAddPackages
#		Add packages for g++ and gcc version 8.       
#
#    Enter:
#       none
#    Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
# =======================================================================
function ccAddPackages()
{
	while (true)
	do
	    pkgAddItemList "fakeroot g++ g++-8 gcc gcc-8"
		[[ $? -eq 0 ]] || break

	    pkgAddItemList "libc-dev-bin libc6-dev libcc1-0 libfakeroot libgcc-8-dev"
		[[ $? -eq 0 ]] || break

	    pkgAddItemList "libitm1 libstdc++-8-dev libubsan1 linux-libc-dev"
		[[ $? -eq 0 ]] || break

		break

	done
	
	return $?
}

# ===================================================================================
#
#	ccInstall
#        Install required packages from cclib
#
#    Enter:
#        none
#    Exit:
#        0 = no error
#        non-zero = error code
#
# ===================================================================================
function ccInstall()
{
echo "ccInstall in cc.sh"

    ccAddPackages
    [[ $? -eq 0 ]] || 
     {
		echo "ccInstall failed ccAddPackages ($?)"
		return 1
	 }

    pkgExecute
    [[ $? -eq 0 ]] || 
     {
		echo "pkgExecute failed ($?)"
		return 2
	 }

    pkgReset

    return 0
}

