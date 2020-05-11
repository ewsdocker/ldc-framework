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

# ========================================================

apt-get -y update

case ${OJDK_TYPE} in

	"jre8")
	    apt-get -y install \
                   ca-certificates-java \
                   libgtk-3-0 \
                   libgtk-3-common \
	               java-common \
	               openjdk-8-jre \
	               openjdk-8-jre-headless
	    ;;
	    
	"jre11" | "jre13")

		apt-get install -y \
	               java-common 

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

exit 0

