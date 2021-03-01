#!/bin/bash

declare instList=" "

# =========================================================================
#
#	addPkg
#		add specified package to install list
#
#   Enter:
#		instPkg = "package" name to add to the installation list
#		instComment = comment... not used, but tolerated for documentation
#
# =========================================================================
function addPkg()
{
	local instPkg="${1}"
	local instComment="${2}"

    printf -v instList "%s %s" "${instList}" "${instPkg}"
    return 0
}

# =========================================================================
#
#	installList
#		the instList has been built, now execute it
#
#   Enter:
#		none
#
# =========================================================================
function installList()
{
	echo ""
	echo "$instList"
	echo ""

	$instList
	[[ $? -eq 0 ]] || exit $?

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
#		instDir = directory to extract to
#
# =========================================================================
function installPackage()
{
	local instPkg="${1}"
	local instUrl="${2}"
	local instDir="${3}"

    wget "${instUrl}" 
    [[ $? -eq 0 ]] || return $?

    tar -xvf "${instPkg}" -C "${instDir}" 
    [[ $? -eq 0 ]] || return $?

    rm "${instPkg}"

    return 0
}

# ========================================================

#apt-get -y update

instList="apt-get -y install "

addPkg "java-common"

case ${OJDK_TYPE} in

	"jre8" | "jre11")
        addPkg "ca-certificates-java"

	    addPkg "default-jre"
        addPkg "default-jre-headless"
 
        addPkg "libatk-wrapper-java"
        addPkg "libatk-wrapper-java-jni"

        addPkg "libgif7"
        addPkg "libgtk-3-0"
        addPkg "libgtk-3-common"
        addPkg "libnspr4"
        addPkg "libnss3"
        addPkg "libpcsclite1"
 
        addPkg "openjdk-11-jre"
        addPkg "openjdk-11-jre-headless"

		installList
		[[ $? -eq 0 ]] || exit $?

	    ;;
	    
	"jdk8" | "jdk11")
        addPkg "ca-certificates-java"

	    addPkg "default-jdk"
        addPkg "default-jdk-headless"
 
        addPkg "libatk-wrapper-java"
        addPkg "libatk-wrapper-java-jni"

        addPkg "libgif7"
        addPkg "libgtk-3-0"
        addPkg "libgtk-3-common"
        addPkg "libnspr4"
        addPkg "libnss3"
        addPkg "libpcsclite1"
 
        addPkg "openjdk-11-jdk"
        addPkg "openjdk-11-jdk-headless"

		installList
		[[ $? -eq 0 ]] || exit $?

	    ;;
	    
	"jre13")

		installList
		[[ $? -eq 0 ]] || exit $?

	    mkdir -p /usr/lib/jvm

        installPackage ${OJDK_PKG} ${OJDK_URL} "/usr/lib/jvm"

        ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /usr/bin/java
        ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /etc/alternatives/java
        
        ;;
        
    *)
        echo "Unknown OJDK_TYPE = \"${OJDK_TYPE}\""
        exit 1
       
        ;;
esac

# =======================================================================

apt-get clean all

exit 0

