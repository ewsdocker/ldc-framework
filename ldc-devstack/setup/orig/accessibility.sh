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

case ${OJDK_TYPE} in

	"jre8")
	    apt-get -y install \
	               adwaita-icon-theme \
	               ca-certificates-java \
	               libatk-bridge2.0-0 \
	               libatk-wrapper-java \
	               libatk-wrapper-java-jni \
	               libatspi2.0-0 \
	               libcairo-gobject2 \
                   libepoxy0 \
                   libgif7 \
                   libgtk-3-0 \
                   libgtk-3-common \
                   libnspr4 \
                   libnss3 \
                   libpcsclite1 \
                   librest-0.7-0 \
                   libsoup-gnome2.4-1 \
                   libsoup2.4-1 \
	               openjdk-8-jre \
	               openjdk-8-jre-headless

	    ;;
	    
	"jdk8")
	    apt-get -y install \
	               ca-certificates-java \
                   libgtk-3-0 \
                   libgtk-3-common \
	               openjdk-8-jdk \
	               openjdk-8-jdk-headless \
	               \
	               libatk-bridge2.0-0 \
	               libatk-wrapper-java \
	               libatk-wrapper-java-jni \
	               libatspi2.0-0 \
	               \
                   libnspr4 \
                   libnss3 \
                   librest-0.7-0 \
                   libsoup-gnome2.4-1 \
                   libsoup2.4-1 \
	               

	    ;;
	    
	"jdk13" | "jre13")
	
	    mkdir -p /usr/lib/jvm

        installPackage ${OJDK_PKG} ${OJDK_URL} "/usr/lib/jvm"

        ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /usr/bin/java
        ln -s /usr/lib/jvm/jdk-${OJDK_VERS}/bin/java /etc/alternatives/java
        
        ;;
        
    *)
        echo "Unknown JDK_TYPE = \"${OJDK_TYPE}\""
        exit 1
       
        ;;
esac

exit 0