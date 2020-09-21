#!/bin/bash

# ===================================================================================
# ===================================================================================
#
#	gfortran8lib.sh
#       Install requested gfortran8lib
#
# ===================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-applications
# @subpackage eclipse/gfortran8lib
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
#    gfAddPackages
#		Add packages for gfortran version 8.       
#
#    Enter:
#       none
#    Exit:
#       0 = no error
#       non-zero = error code
#
# ===================================================================================
# =======================================================================
function gfAddPackages()
{
	while (true)
	do
		pkgAddItemList "gfortran gfortran-8 libgfortran-8-dev libgfortran5"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "libcoarrays-dev libcaf-openmpi-3 libcaf-mpich-3"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "libfabric1 libhwloc-plugins libhwloc5"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "libibverbs1 libnl-route-3-200 libopenmpi3 libpsm-infinipath1 librdmacm1"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "mpi-default-bin ocl-icd-libopencl1 openmpi-bin openmpi-common pciutils"
		[[ $? -eq 0 ]] || break

		pkgAddItemList "libevent-2.1-6 libevent-core-2.1-6 libevent-pthreads-2.1-6"
		[[ $? -eq 0 ]] || break
	
		pkgAddItemList "libmpich12 libpmix2 libpsm2-2 openssh-client"
		[[ $? -eq 0 ]] || break
		
		break
	done
	
	return $?
}

# ===================================================================================
#
#	gfInstall
#        Install required packages from gfortran8lib
#
#    Enter:
#        none
#    Exit:
#        0 = no error
#        non-zero = error code
#
# ===================================================================================
function gfInstall()
{
    gfAddPackages
    [[ $? -eq 0 ]] || 
     {
		echo "gfInstall failed gfAddPackages ($?)"
		return 1
	 }

    pkgExecute
    [[ $? -eq 0 ]] || 
     {
		echo "pkgExecute failed ($?)"
		return 2
	 }

    pkgReset

    return 0
}

