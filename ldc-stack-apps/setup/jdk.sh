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

function installList()
{
	echo ""
	echo "$instList"
	echo ""

	$instList

	return $?
}

# ===============================================

echo "*****************************************************"
echo ""
echo "   ldc-devstack ${OJDK_TYPE}"
echo ""
echo "*****************************************************"

apt-get -y update

addPkg "apt-get -y install" 

addPkg "ca-certificates-java"
addPkg "default-jre-headless"

addPkg "dbus-java-bin"
addPkg "java-common " 
addPkg "libdbus-java"
#addPkg "libdbus-java-doc"
addPkg "libmatthew-debug-java"
addPkg "libnspr4"
addPkg "libnss3"
addPkg "libpcsclite1"
addPkg "libunixsocket-java"

addPkg "openjdk-8-jre-headless" 

case ${OJDK_TYPE} in

  "jdk8" | "jre8")

		addPkg "default-jre"

		addPkg "libatk-wrapper-java"
		addPkg "libatk-wrapper-java-jni"
		addPkg "libgif7"
		addPkg "libgtk-3-0"
		addPkg "libgtk-3-common"

		echo "*****************************************************"
		echo ""
		echo "   Processing ${OJDK_TYPE}"
		echo ""
		echo "*****************************************************"

    	[[ "${OJDK_TYPE}" -eq "jdk8" ]] &&
    	 {
   			addPkg "openjdk-8-jdk"
   			addPkg "openjdk-8-jdk-headless"
		 }

		addPkg "openjdk-8-jre"

    	installList
		[[ $? -eq 0 ]] || exit 3
		
		;;

	"jre13" | "jdk13")

    	mkdir -p /usr/lib/jvm

    	installPackage ${OJDK_PKG} ${OJDK_URL} "/usr/lib/jvm"

    	installList
		[[ $? -eq 0 ]] || exit 3

#    	ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /usr/bin/java
#    	ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /etc/alternatives/java

    	;;

  	*)  
    	echo "Unknow type: ${OJDK_TYPE}"
    	exit 1
    	
    	;;
esac

exit 0
