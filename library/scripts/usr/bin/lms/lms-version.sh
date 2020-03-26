#!/bin/bash
# =========================================================================
# =========================================================================
#
#	version
#	  Print version information
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.2
# @copyright © 2018. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/lms-utilities
# @subpackage lms-version
#
# =========================================================================
#
#	Copyright © 2018. EarthWalk Software
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
# =========================================================================

. /usr/local/lib/lms/lmsconCli.lib
. /usr/local/lib/lms/lmsDisplay.lib
. /usr/local/lib/lms/lmsVersion.lib

# =========================================================================

lmsDisplay ""
lmsDisplay "***********************************************************************"
lmsDisplay ""

lmsVersion

lmsDisplay ""
lmsDisplay "***********************************************************************"
lmsDisplay ""

