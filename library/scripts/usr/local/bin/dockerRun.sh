#!/bin/bash
# =========================================================================
# =========================================================================
#
#	dockerRun
#	  Creates a new container, if needed, then starts it.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.7
# @copyright © 2017-2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-library
# @subpackage dockerRun
#
# =========================================================================
#
#	Copyright © 2017-2019. EarthWalk Software
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
# =========================================================================
# =========================================================================
#
#	A simple state machine based on stately binary functions.
#
#	Each function 
#		- processes a single state and 
#		- returns a binary result (0 = true, 1 = false) 
#		    used to select the next state
#
#	The state machine engine processes the next state based on the 
#		returned result of the current state, until one of the exit 
#		states has been reached.
#
# =========================================================================
#
#	state	function			true	false
#	-----	---------------		----	-----
#
#	  0		dqInitProcessor	 	 1		  7
#	  1		dqGetContainer	 	 3		  2
#	  2		dqCreateContainer 	 3		  7
#	  3		dqCheckRunning	 	 6		  4
#	  4		dqStartContainer	 5		  7
#	  5		dqWaitEvent	     	 6		  7
#	  6		(exit - normal)	 	 -		  -
#	  7		(exit - error)	 	 -		  -
#
# =========================================================================
# =========================================================================
#
#	Runs in the docker host system, NOT in the container!
#
# =========================================================================
# =========================================================================

# ========================================================================
# ========================================================================
#
# The following block must remain in the same order, 
#   and must appear prior to any function declarations.
#
#	NO LIBRARIES HAVE BEEN LOADED YET!
#
# ========================================================================
# ========================================================================

declare -a cliBuffer=( "${@}" )

[[ ${#cliBuffer[*]} -lt 1 ]] && 
 {
    echo "Missing container name"
    exit 2
 }

# ========================================================================
# ========================================================================

declare -i pkgcache=1			# 1 = use local pkgcache, 0 = use git-hub version 

declare    ldc_repo="ewsdocker" # repository name

declare    ldc_libvers="0.1.5"	# ldc library version

declare -i ldc_optDev=1

# ========================================================================

declare    ldc_cfgroot="${HOME}/.config/docker"
								# docker container configuration root folder

declare    ldc_libcfgroot="${ldc_cfgroot}-libs"
								# docker library configuration root folder

# ========================================================================

declare -a ldc_cnames=()		# cli input in an array

declare    ldc_iname=""			# image name (e.g. - ldc-core:dcore-0.1.0 )
declare    ldc_container=""		# container name (e.g. - ldc-core-dcore-0.1.0 )
declare    ldc_rname=""			# container run name (e.g. - ldc-core-dcore-0.1.0 )

declare    ldc_start=""			# name of the container to start/create

# ========================================================================
# ========================================================================
#
#	pre-run initialize - no external libraries required for prInitialize
#
# ========================================================================
# ========================================================================

#
# From github.com
# 		https://github.com/ewsdocker/ldc-library/releases/download/ldc-library-0.1.5/ldc-library-0.1.5.tar.gz
#
# or local pkgcache
#		http://alpine-nginx-pkgcache/ldc-library-0.1.5.tar.gz
#

declare    ldc_libname="ldc-library"

declare    ldc_library="${ldc_libcfgroot}/${ldc_libname}"
declare    ldc_libloc="${ldc_library}/${ldc_libvers}"

declare    ldc_libpname="${ldc_libname}-${ldc_libvers}"
declare    ldc_libpkg="${ldc_libpname}.tar.gz"

declare    ldc_libsrv="https://github.com"
declare    ldc_libdevname="ldc-library"
declare    ldc_libsrvpath="ewsdocker/${ldc_libdevname}/releases/download/${ldc_libpname}"

declare    ldc_libhost="${ldc_libsrv}/${ldc_libsrvpath}"}

	[[ ${ldc_optDev} -eq 0 ]] || ldc_libhost="172.32.0.2"
	
declare    ldc_liburl="${ldc_libhost}/${ldcpkg}"

# ========================================================================
# ========================================================================
#
#	Pre-run functions
#
# ========================================================================
# ========================================================================

# ========================================================================
#
#   prInstallLib
#
#  		Download and install the required ldc-library.
#
#   parameters:
#		None
#
#	returns:
#      0 = no error
#      non-zero = error code
#
#	  1	(if optDev is not 0 or 
#			
# ========================================================================
function prInstallLib()
{
	ldc_libhost=${1:-"$ldc_libhost"}
	ldc_libpkg=${2:-"$ldc_libpkg"}

	ldc_liburl="${ldc_libhost}/${ldc_libpkg}"

	local -i inerror=0
	local -i ldc_libreq=0
	local    ldc_parent=""

	if [ -e "${ldc_libloc}" ]; then ldc_libreq=1 ; fi

	while [ ${ldc_libreq} -eq 0 ]
	do
	 	mkdir -p "${ldc_libloc}"
	 	inerror=$?

	 	[[ ${inerror} -eq 0 ]] || break

		ldc_parent="${PWD}"

		cd ${ldc_libloc}

			wget -q "${ldc_liburl}" >/dev/null 2>/dev/null
			inerror=$?

	 		[[ ${inerror} -eq 0 ]] || break

			tar -xvf "${ldc_libpkg}" >/dev/null 2>/dev/null
			inerror=$?

	 		[[ ${inerror} -eq 0 ]] || break

			rm "${ldc_libpkg}"
			inerror=$?

		break
	done

	[[ -n ${ldc_parent} ]] && cd ${ldc_parent}

	return ${inerror}
}

# ========================================================================
#
#   prInitialize
#
#  		Initialize the pre-run variables to ensure a successful startup.
#
#   parameters:
#		None
#
#	returns:
#      0 = no error
#      non-zero = error code
#
# ========================================================================
function prInitialize()
{
	#
	# split the container name (in cliBuffer[0]) at the ":"
	#
	OIFS="$IFS"
	IFS=":"
    	read -a ldc_cnames <<< "${cliBuffer[0]}"
	IFS="$OIFS"

	#
	# create container name variants
	#
	if [[ ${#ldc_cnames[@]} -eq 2 ]] 
	then
		ldc_iname="${ldc_cnames[0]}:${ldc_cnames[1]}"
		ldc_container="${ldc_cnames[0]}-${ldc_cnames[1]}"
		ldc_rname="${ldc_container}"
	else
		ldc_iname="${ldc_cnames[0]}:latest"
		ldc_container="${ldc_cnames[0]}-latest"
		ldc_rname="${ldc_cnames[0]}"
	fi

	# ====================================================================

	if [ ! -e "${ldc_cfgroot}/${ldc_container}/lms-base.conf" ]
	then
	 	echo "LMS_BASE=${HOME}/.local" > ${ldc_cfgroot}/${ldc_container}/lms-base.conf
 	fi

	# ====================================================================

	source ${ldc_cfgroot}/${ldc_container}/lms-base.conf

	ldc_root=${LMS_BASE}
	
	ldc_binpath=${ldc_libloc}/usr/local/bin
	ldc_libpath=${ldc_libloc}/usr/local/lib/lms
	ldc_sharepath=${ldc_libloc}/usr/local/share/lms
}

# ========================================================================
#
#   preRun
#
#  		Initialize variables and install libraries.
#
#   parameters:
#		None
#
#	returns:
#      0 = no error
#      non-zero = error code
#
# ========================================================================
function preRun()
{
	prInstallLib
	[[ $? -eq 0 ]] ||
	 {
		 echo "Unable to install library \"${ldc_libpkg}\". (prInstallLib)"
		 return $inerror
	 }

	echo -n "Library \"${ldc_libpkg}\" installed in: "
	echo "      ${ldc_libloc}"

	prInitialize
	[[ $? -eq 0 ]] ||
	 {
		 echo "Unable to complete the docker run initialization (prInitialize)."
		 return $inerror
	 }

	echo "Pre-initialization complete."
	return 0
}

# ========================================================================
# ========================================================================
#
#	State machine starts here
#
# ========================================================================
# ========================================================================

#
#	Required Libraries
#

. ${ldc_libpath}/lmsBashVars.lib
. ${ldc_libpath}/lmsconCli.lib
. ${ldc_libpath}/lmsDeclare.lib
. ${ldc_libpath}/lmsDisplay.lib
. ${ldc_libpath}/lmsDockerQuery.lib
. ${ldc_libpath}/lmsParseColumns.lib
. ${ldc_libpath}/lmsStr.lib
. ${ldc_libpath}/lmsUtilities.lib

#
#	only refer to dq_ vars, not ldc_ vars, except in dqInitProcessor (state 0).
#

# ========================================================================
#
#   dqInitProcessor
#
#  		Initialize the state processor variables and
#		  validate the container.
#
#   parameters:
#		None
#
#	returns:
#      0 = no error
#      non-zero = error code
#
# ========================================================================
function dqInitProcessor()
{
	dq_error=${dqerror_none}

	lmsCliParse
	[[ $? -eq 0 ]] || dq_error=${dqerror_parse_cli}
	
	while [ ${dq_error} == ${dqerror_none} ]
	do

		for key in ${cliKey[@]}
		do
			case $key in 

				"repo")
					lmsDeclareStr "ldc_repo" "${cliParam[$key]}"
					;;

				"image")
					lmsDeclareStr "ldc_iname" "${cliParam[$key]}"
					;;

				"container")
					lmsDeclareStr "ldc_container" "${cliParam[$key]}"
					;;

				"runlabel")
					lmsDeclareStr "ldc_rname" "${cliParam[$key]}"
					;;

				*)
					lmsDeclareStr "ldc_start" "${cliParam[$key]}"
					;;

			esac
		done

		[[ ${dq_error} -eq ${dqerror_none} ]] || break

		dqInitialize

		dqSetNames "${ldc_repo}" "${ldc_iname}" "${ldc_container}" "${ldc_rname}"
		[[ ${dq_error} -eq ${dqerror_none} ]] || break

    	# ====================================================================
    	# ====================================================================
		#
		#	Use dq_ prefixes instead of ldc_ prefixes 
		#		after executing the dqSetNames function
		#
    	# ====================================================================
    	# ====================================================================

		dqCheckImage

#		[[ ${dq_error} -eq ${dqerror_none} ]] || dq_error=${dqerror_init}

		break
	done

	return ${dq_error}
}

# ========================================================================
#
#   execContainer
#
#   	returns 0 if able to start, error code if not
#
#   parameters:
#		None
#
#	returns:
#      	0 = no error
#      	non-zero = error code
#
# ========================================================================
function execContainer()
{
	local cstate=0
	local cnext=0

	dq_error=${dqerror_none}

	while [ true ]
	do

		case ${cstate} in
		
			0)	dqInitProcessor
				[[ $? -eq 0 ]] && cnext=1 || cnext=7

				;;


			1)	dqGetContainer
				[[ $? -eq 0 ]] && cnext=3 || cnext=2

				;;


			2)	dqCreateContainer
				[[ $? -eq 0 ]] && cnext=3 || cnext=7

				;;


			3)	dqCheckRunning
				[[ $? -eq 0 ]] && cnext=6 || cnext=4

				;;


			4)	dqStartContainer
				[[ $? -eq 0 ]] && cnext=5 || cnext=7

				;;


			5)	dqWaitEvent $dq_eventRunning 30
				[[ $? -eq 0 ]] && cnext=6 || cnext=7

				;;

			6)	dq_error=${dqerror_none}
				cnext=98

				break

				;;

			7)	cnext=99

				break

				;;

			*)	dq_error=${dqerror_state}
				break

				;;

		esac

		cstate=${cnext}

	done

	return ${dq_error}
}

# ========================================================================
# ========================================================================
#
#		Start Application Here
#
# ========================================================================
# ========================================================================

lmscli_optQuiet=0

preRun
[[ $? -eq 0 ]] ||
 {
 	echo "  pre-run failed!"
	exit ${inerror}
 }

lmsDisplay "  Processing \"${ldc_container}\""

execContainer
[[ $? -eq 0 ]] || 
 {
 	lmsDisplay "Unable to start \"${dq_container}\""
 	lmsDisplay "Error: ($dq_error) ${dqerror_message[$dq_error]}"
 }

exit ${dq_error}
