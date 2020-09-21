
# ===================================================================================
# ===================================================================================
#
#	jdk13lib.sh
#       Add jdk13lib Version repository to APT repository, 
#       Install requested jdk13lib from the APT repository.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-applications
# @subpackage eclipse/jdk13lib
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
#    jdkAddItems
#       add selected items to the pkgList buffer
#
#    Enter:
#       jdkItem = the package designation to add
#       jdkComment = an unused string for documentation purposes
#    Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function jdkAddItems()
{
    pkgAddItemList "java-common default-jre"
    [[ $? -eq 0 ]] || return $?

    return 0
}

# ===================================================================================
#
#    jdkInstallArchive
#       Install the JDK-13 archive from web-site.
#
#    Enter:
#       jdkPkg = name of the required JDK release
#       jdkUrl = address of the jdkPkg file on the internet
#    Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function jdkInstallArchive()
{
    local jdkPkg=${1:-""}
    local jdkUrl=${2:-""}
 
    [[ -z "${jdkPkg}" ]] && return 1

    mkdir -p /usr/lib/jvm

    pkgInstallArchive ${jdkPkg} ${jdkUrl} "/usr/lib/jvm"
    [[ $? -eq 0 ]] || return 1

    ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /usr/bin/java
    ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /etc/alternatives/java

    return 0
}

# ===================================================================================
#
#    Enter:
#        jdkPkg
#        jdkUrl
#    Exit:
#        0 = no error
#        non-zero = error code
#
# ===================================================================================
function jdkInstall()
{
    local jdkPkg=${1:-"$OJDK_PKG"} 
    local jdkUrl=${2:-"$OJDK_URL"}

    jdkAddItems
    [[ $? -eq 0 ]] || 
	 {
		echo "jdkInstall failed in jdkAddItems ($?)"
		return 1
	 }

    pkgExecute
    [[ $? -eq 0 ]] ||
	 {
		echo "jdkInstall failed in pkgExecute ($?)"
		return 2
	 }

    jdkInstallArchive ${jdkPkg} ${jdkUrl}
    [[ $? -eq 0 ]] ||
	 {
		echo "jdkInstall failed in jdkInstallArchive ($?)"
		return 3
	 }

    return 0
}

