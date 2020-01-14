#!/bin/bash
# ========================================================================================
# ========================================================================================
#
#    entrypoint.sh
#      entrypoint for ldc-library:dbase based builds.
#
# ========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.2
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-library
# @subpackage entrypoint
#
# ========================================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-library.
#
#   ewsdocker/ldc-library is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-library is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-library.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# ========================================================================================
# ========================================================================================

. /usr/local/lib/lms/lmsBashVars.lib
. /usr/local/lib/lms/lmsUtilities.lib
. /usr/local/lib/lms/lmsDeclare.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsStr.lib

lmscli_optQuiet=${LMSOPT_QUIET}

l_com=""

if [ -z "${@}" ] 
then
    if [ -z "${LRUN_APP}" ] 
    then
    	l_com="/bin/bash"
    else 
    	l_com="${LRUN_APP}"
    fi
else
    if [ -z "${LRUN_APP}" ] 
    then
    	l_com="${@}"
    else
    	l_com="${LRUN_APP} ${@}"
    fi
fi

export "L_COMMAND=${l_com}"

/usr/bin/lms/lms-setup.sh

lmsDisplay
lmsDisplay "Starting: \"${L_COMMAND}\""
lmsDisplay

gosu root:root "${L_COMMAND}"
