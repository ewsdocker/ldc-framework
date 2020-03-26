#!/bin/bash

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

apt-get -y update
apt-get install -y \
        java-common 

case ${JDK_TYPE} in
	"jre8")
	    apt-get -y install openjdk-8-jre openjdk-8-jre-headless
	    ;;
	    
	"jre11")
	    mkdir -p /usr/lib/jvm

        installPackage ${OJDK_PKG} ${OJDK_URL} "/usr/lib/jvm"

        ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /usr/bin/java
        ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /etc/alternatives/java
        ;;
        
    *)
        echo "Unknown JDK_TYPE = \"${JDK_TYPE}\""
        exit 1
       
        ;;
esac

exit 0

