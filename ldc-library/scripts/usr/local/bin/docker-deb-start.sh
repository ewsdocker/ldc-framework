#!/bin/bash
# =========================================================================
# =========================================================================
#
#	debian-deb-start
#	  Creates a new container, if needed, then starts it.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.0.7
# @copyright © 2018, 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/lms-utilities
# @subpackage debian-deb-start
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
# =========================================================================
#
#	A simple state machine based on stately binary functions.  Each function
#		- processes a single state and 
#		- returns a binary result (0 = true, 1 = false) used select the next state
#
#	The state machine engine then selects the next state based on the
#	returned result of the current state, and moves on to process the
#	next state, until the one of the exit states has been reached.
#
# =========================================================================
#
#	state	function			true	false
#	-----	---------------		----	-----
#
#	  0		initProcessor	 	 1		  7
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

declare -a container_fields=()
declare -a cliBuffer=( "${@}" )

# =========================================================================================
# =========================================================================================
#
# The following block must remain in the same order, 
#   and must appear prior to any function declarations.
#
#	NO LIBRARIES HAVE BEEN LOADED YET!
#
# =========================================================================================
# =========================================================================================

[[ ${#cliBuffer} -eq 0 ]] && 
 {
    echo "Missing container name"
    exit 2
 }

# =========================================================================================

declare -a deb_cnames=()		# cli input in an array

declare    deb_repo="ewsdocker" # repository name
declare    deb_iname=""			# image name (e.g. - lmsDockerQuery )

declare    deb_container=""		# container name
declare    deb_rname=""			# container run name

# =========================================================================================

OIFS="$IFS"
IFS=":"
    read -a deb_cnames <<< "${cliBuffer[0]}"
IFS="$OIFS"

if [[ ${#deb_cnames[@]} -eq 2 ]] 
then
	deb_iname="${deb_cnames[0]}:${deb_cnames[1]}"
	deb_container="${deb_cnames[0]}-${deb_cnames[1]}"
	deb_rname="${deb_container}"
else
	deb_iname="${deb_cnames[0]}:latest"
	deb_container="${deb_cnames[0]}-latest"
	deb_rname="${deb_cnames[0]}"
fi

# =========================================================================================

source ${HOME}/.config/docker/${deb_container}/lms-base.conf

deb_root=${LMS_BASE}
deb_binpath=${deb_root}/bin
deb_libpath=${deb_root}/lib/lms
deb_sharepath=${deb_root}/share/lms

# =========================================================================================
# =========================================================================================
#
#	Required Libraries
#
# =========================================================================================
# =========================================================================================

. ${deb_libpath}/lmsDeclare.lib
. ${deb_libpath}/lmsDisplay.lib
. ${deb_libpath}/lmsDockerQuery.lib
. ${deb_libpath}/lmsStr.lib

# =========================================================================================
# =========================================================================================
#
#	only refer to dq_ vars, not deb_ vars, except in initialize.
#
# =========================================================================================
# =========================================================================================

# =========================================================================================
#
#   initProcessor
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
# =========================================================================================
function initProcessor()
{
	lmsCliParse
	[[ $? -eq 0 ]] || dq_error=${dqerror_parse_cli}

	for key in ${cliKey[@]}
	do
		case $key in 

			"repo")
				lmsDeclare "deb_repo" "${cliParam[$key]}"
				;;

			"image")
				lmsDeclare "deb_iname" "${cliParam[$key]}"
				;;

			"container")
				lmsDeclare "deb_container" "${cliParam[$key]}"
				;;

			"runlabel")
				lmsDeclare "deb_rname" "${cliParam[$key]}"
				;;

			*)
				lmsDeclareStr "$key" "${cliParam[$key]}"
				;;

		esac
	done
	
	dqInitialize ${deb_repo} ${deb_iname} ${deb_container} ${deb_rname}

	#
	#	Use dq_ prefixes instead of deb_ prefixes 
	#		after executing the dqSetNames function
	#
	dqCheckImage
	[[ ${dq_error} -eq ${dqerror_none} ]] || dq_error=${dqerror_init}

	return ${dq_error}
}

# =========================================================================================
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
# =========================================================================================
function execContainer()
{
	local cstate=0
	local cnext=0

	dq_error=${dqerror_none}

	while [ true ]
	do

		case ${cstate} in
		
			0)	initProcessor
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
	
# =========================================================================================
# =========================================================================================
#
#		Start Application Here
#
# =========================================================================================
# =========================================================================================

lmscli_optQuiet=0

execContainer
[[ $? -eq 0 ]] || 
 {
 	lmsDisplay "Unable to start \"${dq_container}\""
 	lmsDisplay "Error: ($dq_error) ${dqerror_message[$dq_error]}"
 }

exit ${dq_error}
