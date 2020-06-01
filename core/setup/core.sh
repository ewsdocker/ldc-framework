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
#		the instList has been build, now execute it
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
	[[ $? -eq 0 ]] || return $?

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

# =========================================================================

echo "***********************"
echo ""
echo "   ldc-core:dcore"
echo ""
echo "***********************"

echo 'APT::Install-Recommends 0;' >> /etc/apt/apt.conf.d/01norecommends
echo 'APT::Install-Suggests 0;' >> /etc/apt/apt.conf.d/02nosuggests
sed -i 's/^#\s*\(deb.*multiverse\)$/\1/g' /etc/apt/sources.list

apt-get -y update 

# =======================================================================

addPkg "apt-get -y install"

#
# base
#
addPkg "cron"
addPkg "curl"

addPkg "distro-info-data"

addPkg "less"

addPkg "libcurl4"
addPkg "libcurl3-gnutls" 

#addPkg "libevtlog0"

addPkg "libevt1"
addPkg "libexpat1"

addPkg "libpopt0"

addPkg "libreadline7"

addPkg "locales"
addPkg "lsb-release"

addPkg "mime-support"

addPkg "readline-common"

addPkg "sudo"
addPkg "supervisor"

addPkg "unzip"

addPkg "wget"

addPkg "xz-utils"

addPkg "zip"

#
# unicode
#
#addPkg "libunistring2"

#
# sanitizing
#
addPkg "libatomic1"
addPkg "libtsan0"

#
# syslog
#
addPkg "logrotate"

addPkg "syslog-ng"
addPkg "syslog-ng-core"
addPkg "syslog-ng-mod-journal" 
#addPkg "syslog-ng-mod-json"
addPkg "syslog-ng-mod-mongodb"
addPkg "syslog-ng-mod-sql"

#
# security
#
#addPkg "libhogweed4"

addPkg "libk5crypto3"
addPkg "libkeyutils1"

addPkg "libnghttp2-14"

#addPkg "libp11-kit0"

addPkg "libsasl2-2"
addPkg "libsasl2-modules-db"

#
# ssl
#
#addPkg "libssl1.0.2"
addPkg "libssl1.1"

#
# devices
#
addPkg "libivykis0"

#
# networking
#
#addPkg "libgnutls30"
addPkg "libgssapi-krb5-2" 

#addPkg "libidn2-0"

addPkg "libkrb5-3"
addPkg "libkrb5support0"

addPkg "libnet1"

addPkg "librtmp1"

addPkg "libssh2-1"

#addPkg "libtasn1-6"

addPkg "libwrap0"

#
# Network Multicast
#
addPkg "libpsl5"

#
# database
#
addPkg "libdbi1"

addPkg "libjson-c3"

addPkg "libldap-2.4-2"
addPkg "libldap-common"

addPkg "libmongoc-1.0-0" 

addPkg "libsqlite3-0"

addPkg "libyajl2"

#
# C / C++
#
addPkg "libbson-1.0-0" "Intel Cilk Plus"

addPkg "libc-l10n"

#addPkg "libffi6" "Foreign Function Interface"

addPkg "libglib2.0-0"

addPkg "libmpdec2"

#
# python
#
addPkg "dh-python"

addPkg "libpython-stdlib"

addPkg "libpython2.7-stdlib"
addPkg "libpython2.7-minimal"  

addPkg "libpython3-stdlib"

addPkg "libpython3.7-stdlib"
addPkg "libpython3.7-minimal"  

addPkg "psmisc"

addPkg "python"
addPkg "python-meld3"  
addPkg "python-minimal"
addPkg "python-pkg-resources" 

addPkg "python2.7"
addPkg "python2.7-minimal"

addPkg "python3"
addPkg "python3-minimal" 

addPkg "python3.5"
addPkg "python3.5-minimal"

#
# graphics
#
#addPkg "libgmp10"

# =======================================================================

addPkg "libbfio1"
addPkg "libicu63"
addPkg "libpcre2-8-0"
addPkg "libpython2-stdlib"
addPkg "libsnappy1v5"
addPkg "lsb-base"
addPkg "python2"
addPkg "python2-minimal"
addPkg "python3-distutils"
addPkg "python3-lib2to3"
addPkg "python3.7"
addPkg "python3.7-minimal"
addPkg "sensible-utils"

# =======================================================================

installList

# =======================================================================

apt-get -y dist-upgrade
apt-get clean all 

exit 0
