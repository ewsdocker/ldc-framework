#!/bin/bash
# =========================================================================
# =========================================================================
#
#	stable-browser
#	  install and setup 1 of 4 browsers using "stable" repo.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.1
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-browser
# @subpackage lms-setup
#
# =========================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-browser.
#
#   ewsdocker/ldc-browser is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-browser is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-browser.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	buildBrowser
#		download & install browser executable in /opt and link from /usr/bin
#
#   Enter:
#		name = browser name (firefox, netsurf, palemoon, waterfox)
#		pkg = "package" name downloaded - what you are going to extract from
#		url = server to download the pkg from
#		dir = directory to extract to
#
# =========================================================================
function buildBrowser()
{
	local brwsrName="${1}"
	local brwsrPkg="${2}"
	local brwsrUrl="${3}"
	local brwsrDir="${4}"

    wget "${brwsrUrl}" 
    [[ $? -eq 0 ]] || return $?

    tar -xvf "${brwsrPkg}" -C "${brwsrDir}" 
    [[ $? -eq 0 ]] || return $?

    rm "${brwsrPkg}"

    return 0
}

# =========================================================================

echo
echo "*********** stable-browser ***********"
echo

# =========================================================================

result=0

echo
echo "*********** stable-common ***********"
echo

apt-get update
apt-get upgrade
apt-get -y install \
               gvfs \
               gvfs-bin \
               libaacs0 \
               libcanberra0 \
               libcanberra-gtk0 \
               libcanberra-gtk-module \
               libcanberra-pulse \
               libevent-2.0-5 \
               libevent-pthreads-2.0-5 \
               libnspr4 \
               libnss3 \
               libv4l-0 \
               libx11-protocol-perl 

echo
echo "****************************************"
echo


case "${BRWSR_BUILD}" in
   
    # =====================================================================

    "FFOX") 
		echo "Downloading and installing ${FFOX_NAME}" 

        buildBrowser "${FFOX_GENERIC}" "${FFOX_PKG}" "${FFOX_URL}" "/opt"
        result=$?

        ;;
   
    # =====================================================================

    "NSURF")
		echo "Downloading and installing ${NSURF_NAME}"
		
		mkdir -p "./netsurf" 
        buildBrowser "${NSURF_GENERIC}" "${NSURF_PKG}" "${NSURF_URL}" "./netsurf"
        result=$?

		[[ $? -eq 0 ]] &&
		 {
		 	mkdir -p /opt/netsurf
		 	cp -r ./netsurf/usr/bin/nsgtk /opt/netsurf/netsurf 
 			mv ./netsurf/usr/share/netsurf /usr/share 
			rm -Rf ./netsurf/usr 
		 }

        ;;

     # =====================================================================

    "PMOON")
        echo "Downloading and installing ${PMOON_NAME}"

        buildBrowser "${PMOON_GENERIC}" "${PMOON_PKG}" "${PMOON_URL}" "/opt"
        result=$?

        ;;
   
    # =====================================================================

    "WFOX")
        echo "Downloading and installing ${WFOX_NAME}"

        buildBrowser "${WFOX_GENERIC}" "${WFOX_PKG}" "${WFOX_URL}" "/opt" 
        result=$?

        ;;

    # =====================================================================

	*)
		echo "Unrecognized request"

		result=99

		;;

    # =====================================================================

esac
   
echo ""

[[ $result -eq 0 ]] || exit $result

# =========================================================================

chmod 775 /opt/${BRWSR_NAME}/${LRUN_APP} 
ln -s /opt/${BRWSR_NAME}/${LRUN_APP} /usr/bin/${LRUN_APP} 

exit 0

