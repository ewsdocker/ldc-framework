#!/bin/bash
# ===================================================================================
# ===================================================================================
#
#	jdk.sh
#       Add jdk Version repository to APT repository, 
#       Install requested jdk from the APT repository.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-applications
# @subpackage eclipse/jdk
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

function jdkAddItems()
{
    pkgAddItem "java-common"
    pkgAddItem "default-jre"

    case ${OJDK_TYPE} in

        "jre8" | "jre11" | "jdk8" | "jdk11")

            pkgAddItem "ca-certificates-java"
            pkgAddItem "default-jre-headless"
            pkgAddItem "libatk-wrapper-java"
            pkgAddItem "libatk-wrapper-java-jni"
            pkgAddItem "libgif7"
            pkgAddItem "libgtk-3-0"
            pkgAddItem "libgtk-3-common"
            pkgAddItem "libnspr4"
            pkgAddItem "libnss3"
            pkgAddItem "libpcsclite1"
            pkgAddItem "openjdk-11-jre"
            pkgAddItem "openjdk-11-jre-headless"

	    [[ ${OJDK_TYPE} == "jdk8" || ${OJDK_TYPE} == "jdk11" ]] &&
	     {
	        pkgAddItem "default-jdk"
                pkgAddItem "default-jdk-headless"
 
                pkgAddItem "openjdk-11-jdk"
                pkgAddItem "openjdk-11-jdk-headless"
	     }

		installList
		[[ $? -eq 0 ]] || return 1

	    ;;
	    
	"jre13" | "jdk13")

	    installList
	    [[ $? -eq 0 ]] || return 2

    	    mkdir -p /usr/lib/jvm

    	    pkgInstallPackage ${OJDK_PKG} ${OJDK_URL} "/usr/lib/jvm"

    	    ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /usr/bin/java
    	    ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /etc/alternatives/java

    	    ;;

  	*)  
    	    echo "Unknow type: ${OJDK_TYPE}"
    	    return 3
    	
    	    ;;
    esac

    return 0
}

