# ========================================================================================
# ========================================================================================
#
#    Dockerfile.dlibrary
#      Dockerfile for ldc-library.
#
# ========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.6-b3
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-library
# @subpackage Dockerfile
#
# ========================================================================================
#
#	Copyright © 2017-2020. EarthWalk Software
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

ARG FROM_REPO="ewsdocker"
ARG FROM_PARENT="ldc-core"
ARG FROM_VERS="dcore"
ARG FROM_EXT="-0.1.0"
ARG FROM_EXT_MOD="-b3"

FROM ${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}

# ========================================================================================
# ========================================================================================

ARG FROM_REPO
ARG FROM_PARENT
ARG FROM_VERS
ARG FROM_EXT
ARG FROM_EXT_MOD

# ========================================================================================

ARG BUILD_REGISTRY
ARG BUILD_REPO

ARG BUILD_NAME 
ARG BUILD_VERSION
ARG BUILD_VERS_EXT
ARG BUILD_EXT_MOD

# ========================================================================================

ARG BUILD_TEMPLATE
ARG BUILD_DAEMON

# ========================================================================================

ARG LANG
ARG RUN_APP

# ========================================================================================

ARG ULMSLIB_NAME
ARG ULMSLIB_VERS
ARG ULMSLIB_VERSX

ARG ULMSLIB_DIR
ARG ULMSLIB_DEST

ARG ULMSLIB_ETC_DIR
ARG ULMSLIB_USR_DIR

# ========================================================================================

ARG OPT_DEBUG
ARG OPT_QUIET
ARG OPT_TIMEOUT

# ========================================================================================
# ========================================================================================

ENV \
    LRUN_APP=${RUN_APP:-"lms-library-install"} \
    \
    LMS_DAEMON=${BUILD_DAEMON:-"0"} \
    LMS_TEMPLATE=${BUILD_TEMPLATE:-"run"} \
    \
    LMSOPT_TIMEOUT=${OPT_TIMEOUT:-"30"} \
    LMSOPT_QUIET=${OPT_QUIET:-"1"} \
    LMSOPT_DEBUG=${OPT_DEBUG:-"0"} \
    \
    LMS_BASE="/usr/local" \
    LMS_HOME= \
    LMS_CONF= \
    \
    LMS_REGISTRY=${BUILD_REGISTRY:-""} \
    LMS_REPO=${BUILD_REPO:-"ewsdocker"} \
    \
    LMS_NAME=${BUILD_NAME:-"ldc-library"} \
    LMS_VERSION=${BUILD_VERSION:-"0.1.6"} \
    LMS_VERS_EXT=${BUILD_VERS_EXT:-"-b3"} \
    \
    LMS_PACKAGE="${LMS_PARENT}, Debian 10.4"

ENV \
    LMS_FROM="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    LMS_PARENT="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    \
    LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}" 

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}" 

# ========================================================================================
# ========================================================================================
#
# https://github.com/ewsdocker/ldc-library/releases/download/ldc-library-0.1.6/ldc-library-0.1.6-b1.tar.gz
#
# ========================================================================================
#
#   ULMSLIB_NAME = name of the tarball (w/o version) to create (defaults to "ldc-library")
#   ULMSLIB_DEST = path to the archive storage directory (defaults to "/repo")
#
#   ULMSLIB_USR_DIR  = name of the target directory (defaults to "usr")
#   ULMSLIB_ETC_DIR  = name of the target directory (defaults to "etc/lms")
#
# ========================================================================================
# ========================================================================================

ENV LMSU_NAME=${ULMSLIB_NAME:-"ldc-library"} \
    LMSU_VERS=${ULMSLIB_VERS:-"0.1.6"} \
    LMSU_VERSX=${ULMSLIB_VERSX:-""} \
    LMSU_DEST=${ULMSLIB_DEST:-"/repo"} \
    \
    LMSU_USR_DIR=${ULMSLIB_USR_DIR:-"usr"} \
    LMSU_ETC_DIR=${ULMSLIB_ETC_DIR:-"etc/lms"} 

	# ====================================================================================

ENV LMSU_TARNAME="${LMSU_NAME}-${LMSU_VERS}${LMSU_VERSX}" 
ENV LMSU_TARBALL="${LMSU_TARNAME}.tar" 
ENV LMSU_TARGZIP="${LMSU_TARBALL}.gz" 

# ========================================================================================
#
#   install library scripts in utility folder
#
# ========================================================================================

COPY scripts/. /${LMSU_NAME}

# ========================================================================================
#
#   Create the LMSU folder contents and prepare for archiving and 
#      posting to /repo
#
# ========================================================================================

RUN \
 \
 # ========================================================================
 #
 #  setup ldc-library installer
 #
 # ========================================================================
 \
    chmod -R +x /${LMSU_NAME}/usr/bin/lms/*.sh \
 && chmod -R +x /${LMSU_NAME}/usr/local/lib/lms/* \
 && chmod    +x /${LMSU_NAME}/usr/local/bin/* \
 \
 && ln -s /${LMSU_NAME}/usr/bin/lms/lms-library.sh /usr/bin/lms-library-install \
 \
 # ========================================================================
 #
 #   register the installed software
 #
 # ========================================================================
 \
 && dpkg-query -W > /etc/lms/.installedPackages \
 && printf "%s (%s), %s @ %s\n" ${LMS_DOCKER} ${LMS_FROM} `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt  
 
# =========================================================================

VOLUME /conf
VOLUME /usrlocal

# =========================================================================

WORKDIR /root

# =========================================================================

CMD ["lms-library-install"]
