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

echo "***********************"
echo ""
echo "   ldc-base:dx11-surface"
echo ""
echo "***********************"

apt-get -y update 

# =======================================================================

addPkg "apt-get -y install"

# =======================================================================

#
# X-11 
#
addPkg "libx11-protocol-perl" "perl-based X11 Protocol interface"

addPkg "libxaw7"              "X11 Athena Widget library"

addPkg "libxcomposite1"       "X11 Composite extension library"
addPkg "libxcursor1"          "X cursor management library"

addPkg "libxdamage1"          "X11 damaged region extension library"

addPkg "libxft2"              "FreeType-based font drawing library for X"

addPkg "libxinerama1"         "X11 Xinerama extension library"

addPkg "libxmu6"
addPkg "libxmuu1"

addPkg "libxpm4"

addPkg "libxrandr2"

addPkg "libxshmfence1"

addPkg "libxv1"

addPkg "libxxf86dga1"
addPkg "libxxf86vm1" 

addPkg "x11-utils"
addPkg "x11-xserver-utils"
addPkg "x11-session-utils" "X session manager and related tools"

addPkg "xdg-utils"

#
# SDL - Simple DirectMedia Layer
#
addPkg "libsdl2-2.0-0"

#
# OpenGL functionality
#
addPkg "glew-utils"

addPkg "libdrm-amdgpu1"
addPkg "libdrm-intel1"
addPkg "libdrm-nouveau2"
addPkg "libdrm-radeon1"

addPkg "libepoxy0"

addPkg "libgbm1"

addPkg "libgl1-mesa-glx"
addPkg "libglapi-mesa"
addPkg "libglew2.1"
addPkg "libglu1-mesa"

#addPkg "libtxc-dxtn-s2tc"

addPkg "mesa-utils"

addPkg "mesa-va-drivers"

#
# X Surface: Wayland
#
addPkg "libegl1-mesa"

addPkg "libgl1-mesa-dri"

addPkg "libva-wayland2"

addPkg "libwayland-client0"
addPkg "libwayland-cursor0"
addPkg "libwayland-egl1-mesa"
addPkg "libwayland-server0"

#
# Fonts
#
#addPkg "fontconfig"
addPkg "libfontenc1"
addPkg "x11-xfs-utils" "X font server utilities"
#
# Colors
#
addPkg "liblcms2-2"
addPkg "liblcms2-utils"

addPkg "libcolord2"

#addPkg "colord"
#addPkg "colord-data"
#addPkg "libcolorhug2"

addPkg "libllvm7" "Modular compiler & toolchain technologies, runtime library"
addPkg "libsensors5"
addPkg "libxss1"

addPkg "groff"         "GNU troff text-formatting system"
addPkg "groff-base"    "GNU troff text-formatting system base components"

#
# GStreamer
#
#addPkg "libcap2-bin"

addPkg "libgstreamer-plugins-bad1.0-0"
addPkg "libgstreamer-plugins-base1.0-0"
addPkg "libgstreamer1.0-0"

# =======================================================================

addPkg "libegl-mesa0"
addPkg "libegl1"
addPkg "libfs6"
addPkg "libgl1"
addPkg "libglvnd0"
addPkg "libglx-mesa0"
addPkg "libglx0"
addPkg "libsensors-config"
addPkg "libuchardet0"
addPkg "libwayland-egl1"

# =======================================================================

installList
apt-get clean all 

# =======================================================================

exit 0


