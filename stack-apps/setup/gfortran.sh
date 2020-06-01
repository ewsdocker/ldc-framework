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

apt-get -y upgrade

# =======================================================================

addPkg "apt-get -y install"

addPkg "gfortran"
addPkg "gfortran-8"

addPkg "libcoarrays-dev"
addPkg "libcaf-openmpi-3"
addPkg "libcaf-mpich-3"

addPkg "libfabric1"

addPkg "libgfortran-8-dev"
addPkg "libgfortran5"

addPkg "libhwloc-plugins"
addPkg "libhwloc5"

addPkg "libibverbs1"

addPkg "libnl-route-3-200"

addPkg "libopenmpi3"

addPkg "libpsm-infinipath1"

addPkg "librdmacm1"

addPkg "mpi-default-bin"

addPkg "ocl-icd-libopencl1"

addPkg "openmpi-bin"
addPkg "openmpi-common"

addPkg "pciutils"

# =============================================

addPkg "libevent-2.1-6"
addPkg "libevent-core-2.1-6"
addPkg "libevent-pthreads-2.1-6"
addPkg "libmpich12"
addPkg "libpmix2"
addPkg "libpsm2-2"
addPkg "openssh-client"
  
# =============================================

installList

apt-get clean all
