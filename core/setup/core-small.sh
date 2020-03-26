#!/bin/bash

declare instList=" "

# =========================================================================
#
#	addPkg
#		add specified package to install list
#
#   Enter:
#		instPkg = "package" name to add to the installation list
#
# =========================================================================
function addPkg()
{
	local instPkg="${1}"

    printf -v instList "%s %s" "${instList}" "${instPkg}"
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

addPkg "bash-completion cron curl"
addPkg "less libcurl3-gnutls locales logrotate"
addPkg "lsb-release "
addPkg "sudo supervisor syslog-ng syslog-ng-core"
addPkg "unzip wget zip"

$instList

# =======================================================================

apt-get -y dist-upgrade 
 
apt-get clean all 

exit 0
