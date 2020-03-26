#!/bin/bash

declare instList=""

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

apt-get -y update

cho "*******************************"
echo
echo "          QT Common"
echo
echo "*******************************"
echo

addPkg "apt-get -y install"

addPkg "bsdmainutils"
addPkg "fontconfig"
addPkg "groff"
addPkg "groff-base"
addPkg "libjpeg62-turbo"
addPkg "libpipeline1"
addPkg "qtchooser"
addPkg "libjbig0"
addPkg "liblcms2-2"
addPkg "libmng1"
addPkg "libtiff5 

case "${QTVER}" in

	"QT4")

		echo "*******************************"
	    echo
	    echo "          QT 4"
	    echo
	    echo "*******************************"
		echo

		addPkg "libaudio2"
		addPkg "libqt4-designer"
		addPkg "libqt4-dev"
		addPkg "libqt4-network"
		addPkg "libqt4-opengl-dev"
		addPkg "libqt4-qt3support"
		addPkg "libqt4-script"
		addPkg "libqt4-sql"
		addPkg "libqt4-sql-mysql"
		addPkg "libqt4-xml"
		addPkg "libqtcore4"
		addPkg "libqtdbus4"
		addPkg "libqtgui4"
		addPkg "qt-at-spi"
		addPkg "qt4-qtconfig"
		addPkg "qtcore4-l10n"
		addPkg "libdrm-amdgpu1"
		addPkg "libdrm-dev"
		addPkg "libdrm-intel1"
		addPkg "libdrm-nouveau2"
		addPkg "libdrm-radeon1"
		addPkg "libgl1-mesa-dev"
		addPkg "libglu1-mesa"
		addPkg "libglu1-mesa-dev"
		addPkg "libmariadbclient18"
		addPkg "libqt4-dbus"
		addPkg "libqt4-declarative"
		addPkg "libqt4-dev-bin"
		addPkg "libqt4-help"
		addPkg "libqt4-opengl"
		addPkg "libqt4-scripttools"
		addPkg "qt4-dev-tools"

		;;

	"QT5")
		echo "*******************************"
	    echo
	    echo "          QT 5"
	    echo
	    echo "*******************************"
		echo

		addPkg "libdouble-conversion1"
		addPkg "libevdev2"
		addPkg "libgraphite2-3"
		addPkg "libgudev-1.0-0"
		addPkg "libharfbuzz0b"
		addPkg "libicu57"
		addPkg "libinput-bin"
		addPkg "libinput10"
		addPkg "libmtdev1"
		addPkg "libpcre16-3"
		addPkg "libproxy1v5"
		addPkg "libqt5core5a"
		addPkg "libqt5dbus5"
		addPkg "libqt5gui5"
		addPkg "libqt5network5"
		addPkg "libqt5svg5"
		addPkg "libwacom-common"
		addPkg "libwacom2"
		addPkg "libxcb-icccm4"
		addPkg "libxcb-image0"
		addPkg "libxcb-keysyms1"
		addPkg "libxcb-randr0"
		addPkg "libxcb-render-util0"
		addPkg "libxcb-util0"
		addPkg "libxcb-xinerama0"
		addPkg "libxcb-xkb1"
		addPkg "libxkbcommon-x11-0"
		addPkg "mesa-utils"
		addPkg "qt5-gtk-platformtheme"
		addPkg "qt5-image-formats-plugins"
		addPkg "qttranslations5-l10n"
		addPkg "qtwayland5"
		;;

	*)
	    echo "*******************************"
	    echo
	    echo "  Unknown QT option: ${QTVER}"
	    echo
	    echo "*******************************"
		echo

		exit 1

		;;

esac

echo "QT Exit"
echo

apt-get clean all

exit 0

