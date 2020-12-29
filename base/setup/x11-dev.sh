#!/bin/bash

declare instList="apt-get -y install"

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
    [[ $? -eq 0 ]] || exit $?

    return 0
}

# =========================================================================

addPkg "libdrm-dev"

addPkg "libgl1-mesa-dev"
addPkg "libglu1-mesa-dev"

addPkg "libpthread-stubs0-dev"

addPkg "libx11-dev"
addPkg "libx11-xcb-dev"

addPkg "libxau-dev"

addPkg "libxcb-dri2-0-dev"
addPkg "libxcb-dri3-dev"
addPkg "libxcb-glx0-dev"

addPkg "libxcb-present-dev"

addPkg "libxcb-randr0-dev"
addPkg "libxcb-render0-dev"

addPkg "libxcb-shape0-dev"
addPkg "libxcb-sync-dev"

addPkg "libxcb-xfixes0-dev"

addPkg "libxcb1-dev"

addPkg "libxdamage-dev"
addPkg "libxdmcp-dev"

addPkg "libxext-dev"

addPkg "libxfixes-dev"

addPkg "libxshmfence-dev"

addPkg "libxxf86vm-dev"

addPkg "mesa-common-dev"

addPkg "x11proto-core-dev"
addPkg "x11proto-damage-dev"
addPkg "x11proto-dri2-dev"

addPkg "x11proto-fixes-dev"
addPkg "x11proto-gl-dev"
addPkg "x11proto-input-dev"

addPkg "x11proto-kb-dev"

addPkg "x11proto-xext-dev"
addPkg "x11proto-xf86vidmode-dev"

addPkg "xorg-sgml-doctools"

addPkg "xtrans-dev"

# ======================================

addPkg "libgles1"
addPkg "libgles2"
addPkg "libglvnd-core-dev"
addPkg "libglvnd-dev"
addPkg "libopengl0"
addPkg "x11proto-dev"

# ======================================

installList
[[ $? -eq 0 ]] || exit $?

apt-get clean all

exit 0

