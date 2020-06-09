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

# =======================================================================
# =======================================================================

echo "*****************************************************"
echo ""
echo "   Installing NodeJS 14 from sury"
echo ""
echo "*****************************************************"

wget ${NJS_URL}; chmod +x ${NJS_NAME}; ./${NJS_NAME}; rm ${NJS_NAME}

apt-get -y update

# =======================================================================

addPkg "apt-get -y install"

addPkg "build-essential"

addPkg "dpkg-dev"

addPkg "libdpkg-perl"

addPkg "nodejs"

# =======================================================================

echo ""
echo "$instList"
echo ""

$instList
[[ $? -eq 0 ]] || exit $?

apt-get clean all

