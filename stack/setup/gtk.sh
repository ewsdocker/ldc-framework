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
	[[ $? -eq 0 ]] || exit $?

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

# =======================================================================

echo "***********************"
echo ""
echo "   ldc-gtk: ${GTK_VER}"
echo ""
echo "***********************"

apt-get -y update

addPkg "apt-get -y install"

#
#
# ATK Accessibility Toolkit
#
addPkg "at-spi2-core"

addPkg "libatspi2.0-0"
addPkg "libatk1.0-0"
addPkg "libatk1.0-data"
addPkg "libatk-bridge2.0-0"

#
# Cups Printing System
#
addPkg "cups-common"

addPkg "libcups2"

#
# GNOME Accessibility
#
addPkg "libgail18"
addPkg "libgail-common"

#
# GTK-2 libraries
#
addPkg "libgtk2.0-0"
addPkg "libgtk2.0-common"

#
#	Icon Themes
#
addPkg "adwaita-icon-theme"

addPkg "gnome-icon-theme"
addPkg "gtk-update-icon-cache"

addPkg "hicolor-icon-theme"

#
#	Fonts/Font Themes
#
#addPkg "libgraphite2-3"
#addPkg "librsvg2-2"
addPkg "librsvg2-common"

#
#	Cairo 2D vector graphics library
#
addPkg "libcairo-gobject2"

# =======================================================================

#
#  Text layout
#
#addPkg "libpango-1.0-0"
#addPkg "libpangocairo-1.0-0"
#addPkg "libpangoft2-1.0-0"

#addPkg "libharfbuzz0b"

#
#  CSS
#
#addPkg "libcroco3"

#
#   HTTP Library in C
#
addPkg "libsoup-gnome2.4-1"
addPkg "libsoup2.4-1"

# =======================================================================

#
#	Web - REST service
#
addPkg "librest-0.7-0"

#
#	WebKit
#
#addPkg "libwebkit2gtk-4.0.37" "Web content engine library for GTK+"
#addPkg "libjavascriptcoregtk-4.0-18" "JavaScript engine library from WebKitGTK+"

# =======================================================================

[[ "${GTK_VER}" == "GTK2" ]] &&
 {
    #
    # GTK-2 executable
    #
    addPkg "libgtk2.0-bin"
 }

# =======================================================================

[[ "${GTK_VER}" == "GTK3" ]] &&
 {

    #
    # GTK-3/4 dbus menu library
    #
    addPkg "libdbusmenu-gtk3-4"
    addPkg "libdbusmenu-gtk4"

    #
    # GTK-3 executable
    #
    addPkg "libgtk-3-0"
    addPkg "libgtk-3-bin"
    addPkg "libgtk-3-common"
    addPkg "libgtkextra-3.0"
 }

# =======================================================================

installList

apt-get clean all

exit 0
