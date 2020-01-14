#!/bin/bash
# =========================================================================
# =========================================================================
#
#	install-flashplayer.sh
#	  Run a library script to install/upgrade the current flashplayer
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.2
# @copyright © 2018, 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/lms-utilities
# @subpackage install-flashplayer.sh
#
# =========================================================================
#
#	Copyright © 2018, 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/lms-utilities.
#
#   ewsdocker/lms-utilities is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/lms-utilities is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/lms-utilities.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
#
#		Version 0.0.1 - November 28, 2018.
#               0.0.2 - February 27, 2019.
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#   Global variables
#
# =========================================================================

declare status=0
declare flashTemp="/flashtemp"
declare startDir

# =========================================================================
#
#   External libraries - must be loaded prior to calling subroutines
#
# =========================================================================

. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsInstallFlash.lib

# =========================================================================
#
#	Start application here
#
# =========================================================================

lmscli_optQuiet=${LMSOPT_QUIET}

lmsDisplay "" 
lmsDisplay "Installing FlashPlayer ${flashplayerName}." 
lmsDisplay "" 

startDir="${PWD}"

mkdir ${flashTemp}
cd ${flashTemp}

installFlash "${flashplayerFolder}" "${flashplayerModule}" "${flashplayerName}" "${flashplayerMaster}"
status=$?

lmsDisplay "" 

case ${status} in

	0)  lmsDisplay "Flash player ${flashplayerName} was successfully installed." 
	   	;;

	1)  lmsDisplay "Missing required parameter." 
	    ;;

	2)	lmsDisplay "Cannot get current player version." 
		;;

	3)  lmsDisplay "Unable to read player master URL: ${flashplayerMaster}" 
		;;

	4)	lmsDisplay "Unable to read archive file: ${flashplayerName}" 
		;;

	*)  lmsDisplay "An unknown error (${status}) has occurred." 
	    status=5
		;;
esac

# =========================================================================

cd "${startDir}"
rm -R ${flashTemp}

# =========================================================================
#
#	Application exit
#
# =========================================================================

exit ${status}
