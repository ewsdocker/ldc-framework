
# ===================================================================================
# ===================================================================================
#
#	gtklib.sh
#       Install requested gtklib from the APT repository.
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-applications
# @subpackage eclipse/gtklib
#
# ===================================================================================
#
#	Copyright © 2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-applications.
#
#   ewsdocker/ldc-applications is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-applications is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-applications.  If not, see <http://www.gnu.org/licenses/>.
#
# ===================================================================================
# ===================================================================================

# ===================================================================================
#
#    gtkAddPackages
#		Add packages for GTK-2 or GTK-3       
#
#    Enter:
#       gtkVer = GTK version: GTK2 or GTK3 (defaults to $GTK_VER)
#    Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
function gtkAddPackages()
{
	local gtk=${1:-"$GTK_VER"}

	while (true)
	do
		#
		#
		# ATK Accessibility Toolkit
		#
		pkgAddItemList "at-spi2-core libatspi2.0-0"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "libatk1.0-0 libatk1.0-data libatk-bridge2.0-0"
		[[ $? -eq 0 ]] || break

		#
		# Cups Printing System
		#
		pkgAddItemList "cups-common libcups2"
		[[ $? -eq 0 ]] || break

		#
		# GNOME Accessibility
		#
		pkgAddItemList "libgail18 libgail-common"
		[[ $? -eq 0 ]] || break

		#
		# GTK-2 libraries
		#
		pkgAddItemList "libgtk2.0-0 libgtk2.0-common"
		[[ $? -eq 0 ]] || break

		#
		#	Icon Themes
		#
		pkgAddItemList "adwaita-icon-theme gnome-icon-theme"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "gtk-update-icon-cache hicolor-icon-theme"
		[[ $? -eq 0 ]] || break

		#
		#	Fonts/Font Themes
		#
		pkgAddItemList "librsvg2-common libcairo-gobject2"
		[[ $? -eq 0 ]] || break

		#
		#	Cairo 2D vector graphics library
		#
		pkgAddItemList "libcairo-gobject2"
		[[ $? -eq 0 ]] || break

		# ===================================================================

		#
		#   HTTP Library in C
		#
		pkgAddItemList "libsoup-gnome2.4-1 libsoup2.4-1"
		[[ $? -eq 0 ]] || break

		# ===================================================================

		#
		#	Web - REST service
		#
		pkgAddItemList "librest-0.7-0"
		[[ $? -eq 0 ]] || break

		# ===================================================================

		#
		#	Web - WebKitGTK service
		#
		pkgAddItemList "libwebkit2gtk-4.0.37"
		[[ $? -eq 0 ]] || break

		# ===================================================================

		[[ "${GTK_VER}" == "GTK2" ]] &&
		 {
    		#
    		# GTK-2 executable
    		#
    		pkgAddItem "libgtk2.0-bin"
			[[ $? -eq 0 ]] || break
		 }

		# ===================================================================

		[[ "${GTK_VER}" == "GTK3" ]] &&
		 {

    		#
    		# GTK-3/4 dbus menu library
    		#
			pkgAddItemList "libdbusmenu-gtk3-4 libdbusmenu-gtk4"
			[[ $? -eq 0 ]] || break

    		#
    		# GTK-3 executable
    		#
			pkgAddItemList "libgtk-3-0 libgtk-3-bin libgtk-3-common libgtkextra-3.0"
			[[ $? -eq 0 ]] || break

		 }

		break
    done

	return $?
}

# ===================================================================================
#
#	gtkInstall
#        Install required packages from gtklib
#
#    Enter:
#        none
#    Exit:
#        0 = no error
#        non-zero = error code
#
# ===================================================================================
function gtkInstall()
{
    gtkAddPackages
    [[ $? -eq 0 ]] || 
	 {
		echo "gtkInstall failed in gtkAddPackages ($?)"
		return 1
	 }

    pkgExecute
    [[ $? -eq 0 ]] || 
	 {
		echo "gtkInstall failed in gtkExecute ($?)"
		return 2
	 }

    pkgReset

    return 0
}

