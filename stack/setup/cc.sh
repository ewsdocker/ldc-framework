#!/bin/bash

declare instList=" "

# =========================================================================
#
#   addPkg
#	add specified package to install list
#
#   Enter:
#	instPkg = "package" name to add to the installation list
#	instComment = comment... not used, but tolerated for documentation
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
#   installList
#	the instList has been built, now execute it
#
#   Enter:
#	none
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
#   installPackage
#	download & install requested package
#
#   Enter:
#	instPkg = "package" name downloaded - what you are going to extract from
#	instUrl = server to download the pkg from
#	instDir = directory to extract to
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

# =======================================================================

#apt-get -y update

# =======================================================================

addPkg "apt-get -y install"

addPkg "fakeroot"
addPkg "g++"
addPkg "g++-8"
addPkg "gcc"
addPkg "gcc-8"

addPkg "libc-dev-bin"
addPkg "libc6-dev"
addPkg "libcc1-0"
addPkg "libfakeroot"
addPkg "libgcc-8-dev"
addPkg "libitm1"
addPkg "libstdc++-8-dev"
addPkg "libubsan1"

addPkg "linux-libc-dev"

#addPkg "libcilkrts5"

# =======================================================================

echo ""
echo "$instList"
echo ""

$instList

apt-get clean all 

exit 0
