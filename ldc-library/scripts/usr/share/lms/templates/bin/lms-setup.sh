#!/bin/bash
# =========================================================================
# =========================================================================
#
#	lms-setup
#	  Copy run scripts to local host interface.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.8
# @copyright © 2018, 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-utilities
# @subpackage lms-setup
#
# =========================================================================
#
#	Copyright © 2018, 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-utilities.
#
#   ewsdocker/ldc-utilities is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-utilities is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-utilities.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

# =========================================================================
#
#	Global declartions
#
# =========================================================================

LMS_IMAGE="${LMS_NAME}:${LMS_VERSION}"
[[ "${LMS_VERS_X}" == "latest" ]] && LMS_IMAGE+="${LMS_VERS_MOD}"|| LMS_IMAGE+="${LMS_VERS_EXT}"

declare containerName=${LMS_RUN_NAME}
declare lmsContainer="/conf/${containerName}"

declare lmsLocalBin="/usrlocal/bin"
declare lmsShareApp="/usrlocal/share/applications"
declare lmsShareLms="/usrlocal/share/lms"

declare lmsTemplates="${lmsShareLms}/templates"

declare templateContainer="${lmsTemplates}/${containerName}"
declare applicationContainer="${lmsLocalBin}/${containerName}"
declare desktopFile="${lmsShareApp}/${containerName}.desktop"

# =========================================================================

. /usr/local/lib/lms/lmsconCli.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsVersion.lib

. /usr/local/lib/lms/lmssetupContainer.lib
. /usr/local/lib/lms/lmssetupTemplates.lib
. /usr/local/lib/lms/lmssetupFlash.lib

# =========================================================================

lmscli_optQuiet=${LMSOPT_QUIET}

lmsDisplay ""
lmsDisplay "************************************"
lmsDisplay ""
lmsDisplay "Installing \"${LMS_DOCKER}\"" 
lmsDisplay ""

setupContainer

lmsDisplay ""
lmsDisplay "    Creating run/desktop script(s)."
lmsDisplay ""

setupTemplates
#setupFlash
lmsVersion

lmsDisplay ""
lmsDisplay "************************************"
lmsDisplay ""
lmsDisplay ""
lmsDisplay "Internal setup completed."
lmsDisplay ""

