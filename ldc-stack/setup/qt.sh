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

echo "*******************************"
echo
echo "          QT Common"
echo
echo "*******************************"
echo

addPkg "apt-get -y install"

addPkg "libmng1"       "Multiple-image Network Graphics (MNG) library"
addPkg "libpipeline1"  "pipeline manipulation library"

addPkg "qtchooser"     "Select between Qt development binary versions"

case "${QTVER}" in

	"QT4")

		echo "*******************************"
	    echo
	    echo "          QT 4"
	    echo
	    echo "*******************************"
		echo

        addPkg "libqt4-designer"

        addPkg "libqt4-network"

        addPkg "libqt4-qt3support"

        addPkg "libqt4-script"
        addPkg "libqt4-sql"

        addPkg "libqt4-xml"

        addPkg "libqtcore4"

        addPkg "libqtdbus4"

        addPkg "libqtgui4"

        addPkg "qtcore4-l10n"

		;;

	"QT4DEV")

		echo "*******************************"
	    echo
	    echo "          QT 4"
	    echo
	    echo "*******************************"
		echo

#		addPkg "libaudio2"

		addPkg "libgl1-mesa-dev"
		addPkg "libglu1-mesa-dev"

		addPkg "libmariadbclient18"

        addPkg "libphonon4" "Core Library Phonon (Qt 4 multimedia framework API)"

		addPkg "libqt4-dbus"

		addPkg "libqt4-declarative"
		addPkg "libqt4-designer"

		addPkg "libqt4-dev"
		addPkg "libqt4-dev-bin"

		addPkg "libqt4-help"

		addPkg "libqt4-network"

		addPkg "libqt4-opengl"
		addPkg "libqt4-opengl-dev"

		addPkg "libqt4-qt3support"

		addPkg "libqt4-script"
		addPkg "libqt4-scripttools"

		addPkg "libqt4-sql"
		addPkg "libqt4-sql-mysql"

		addPkg "libqt4-xml"

		addPkg "libqtcore4"
		addPkg "libqtdbus4"

		addPkg "libqtgui4"

addPkg "qdbus"

		addPkg "qt-at-spi"

		addPkg "qt4-dev-tools"

addPkg "qt4-linguist-tools"
addPkg "qt4-qmake"

		addPkg "qt4-qtconfig"

        addPkg "libqt4-sql-sqlite"
        addPkg "libqt4-svg"

        addPkg "libqt4-test"
        addPkg "libqt4-xmlpatterns"

addPkg "mysql-common"

		addPkg "qtcore4-l10n"

addPkg "xorg-sgml-doctools"

		#
		#	development files
		#

addPkg "xtrans-dev"

        addPkg "libdrm-dev"

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

		;;

	"QT5")
		echo "*******************************"
	    echo
	    echo "          QT 5"
	    echo
	    echo "*******************************"
		echo

		addPkg "libqt5core5a"
		addPkg "libqt5dbus5"

		addPkg "libqt5gui5"

		addPkg "libqt5network5"

        addPkg "libqt5qml5"
        addPkg "libqt5quick5"

		addPkg "libqt5svg5"

        addPkg "libqt5waylandclient5"
        addPkg "libqt5waylandcompositor5"
        addPkg "libqt5widgets5"

		addPkg "libwacom-common" "identify wacom tablets model-specific features"
		addPkg "libwacom2"       "identify wacom tablets model-specific features"

        addPkg "libwebpdemux2"   "Lossy compression of images."

		addPkg "qt5-gtk-platformtheme"
		addPkg "qt5-image-formats-plugins"

		addPkg "qttranslations5-l10n"

		addPkg "qtwayland5"

		;;

	*)

		echo "*******************************"
	    echo
	    echo "  Unknown option: ${QTVER}"
	    echo
	    echo "*******************************"
		echo

		exit 1

		;;

esac

installList
[[ $? -eq 0 ]] || exit $?

apt-get clean all

echo
echo "QT Exit"
echo

exit 0

