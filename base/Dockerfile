# ========================================================================================
# ========================================================================================
#
#    Dockerfile
#      Dockerfile for ldc-base:dbase.
#
# ========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0-b2
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-base
# @subpackage Dockerfile.dbase
#
# ========================================================================================
#
#	Copyright © 2017-2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-base.
#
#   ewsdocker/ldc-base is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-base is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-base.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# ========================================================================================
# ========================================================================================

ARG FROM_REPO="ewsdocker"
ARG FROM_PARENT="ldc-base"
ARG FROM_VERS="dbase"
ARG FROM_EXT="-0.1.0"
ARG FROM_EXT_MOD="-b2"

FROM ${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}

# ========================================================================================
# ========================================================================================

ARG FROM_REPO
ARG FROM_PARENT
ARG FROM_VERS
ARG FROM_EXT
ARG FROM_EXT_MOD

# ========================================================================================

ARG RUN_APP

# ========================================================================================

ARG GOSU_REL 
ARG GOSU_VER 
ARG GOSU_HOST

# ========================================================================================

ARG LIB_HOST
ARG LIB_NAME
ARG LIB_VERSION
ARG LIB_VERS_MOD
ARG LIB_INSTALL

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
ARG BUILD_BASE

# ========================================================================================

ARG LANG

# ========================================================================================

ARG OPT_DEBUG
ARG OPT_QUIET
ARG OPT_TIMEOUT

# ========================================================================================

ARG DNAME

# ========================================================================================
# ========================================================================================

ENV DSTACK=${DNAME:-"UNKNOWN"}

ENV \
    LRUN_APP=${RUN_APP:-"bash"} \
    \
    LMS_HOME= \
    LMS_CONF= \
    \
    LMS_DAEMON=${BUILD_DAEMON:-"1"} \
    LMS_TEMPLATE=${BUILD_TEMPLATE:-"daemon"} \
    \
    LMS_BASE=${BUILD_BASE:-"/usr/local"} \
    \
    LMS_REGISTRY=${BUILD_REGISTRY:-""} \
    LMS_REPO=${BUILD_REPO:-"ewsdocker"} \
    \
    LMS_FROM="${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    LMS_PARENT="${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    \
    LMS_NAME=${BUILD_NAME:-"ldc-base"} \
    LMS_VERSION=${BUILD_VERSION:-"dbase"} \
    LMS_VERS_X=${BUILD_VERS_EXT:-"-0.1.0"} \
    LMS_VERS_MOD=${BUILD_EXT_MOD} 

ENV LMS_VERS_EXT="${LMS_VERS_X}${LMS_VERS_MOD}" \
    LMS_PACKAGE="${LMS_PARENT}, Debian 10.4"

ENV \
    LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}" 

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}" 

ENV LMSOPT_TIMEOUT=${OPT_TIMEOUT:-"30"} \
    LMSOPT_QUIET=${OPT_QUIET:-"1"} \
    LMSOPT_DEBUG=${OPT_DEBUG:-"0"} 

# ========================================================================================

ENV LANG_LOCALE=${LANG:-"en_US"}

# ========================================================================================
# ========================================================================================
#
# https://github.com/ewsdocker/ldc-library/releases/download/ldc-library-0.1.6/ldc-library-0.1.6-b1.tar.gz
#
# ========================================================================================
# ========================================================================================

ENV LMSLIB_INST=${LIB_INSTALL:-"0"} \
    LMSLIB_NAME=${LIB_NAME:-"ldc-library"} \
    LMSLIB_VERS=${LIB_VERSION:-"0.1.6"} \
    LMSLIB_VERS_MOD=${LIB_VERS_MOD:-"-b2"} 

ENV LMSLIB_HOST=${LIB_HOST:-"https://github.com/ewsdocker/ldc-library/releases/download/ldc-library-${LMSLIB_VERS}"}

ENV LMSLIB_VERSION="${LMSLIB_VERS}${LMSLIB_VERS_MOD}"
ENV LMSLIB_FULLNAME="${LMSLIB_NAME}-${LMSLIB_VERSION}"

ENV LMSLIB_PKG=${LMSLIB_FULLNAME}.tar.gz 

ENV LMSLIB_URL=${LMSLIB_HOST}/${LMSLIB_PKG}

# ========================================================================================
#
#  https://github.com/tianon/gosu/releases/download/1.4/gosu-amd64
#  https://github.com/tianon/gosu/releases/download/1.10/gosu-amd64
#  https://github.com/tianon/gosu/releases/download/1.12/gosu-amd64
#
# ========================================================================================

ENV GSU_REL=${GOSU_REL:-"1.4"} \
    GSU_VER=${GOSU_VER:-"gosu-amd64"} \
    GSU_HOST=${GOSU_HOST:-"https://github.com/tianon/gosu/releases/download"}

ENV GSU_PKG=${GSU_REL}/${GSU_VER}
ENV GSU_URL=${GSU_HOST}/${GSU_PKG}

# ========================================================================================
# ========================================================================================
#
#   install library and scripts
#
# ========================================================================================
# ========================================================================================

COPY setup/. /tmp/

RUN chmod +x /tmp/*.sh \
 && if test "${DSTACK}" = "BASE"; then /tmp/install_library.sh; fi  

COPY scripts/. /

# =========================================================================
# =========================================================================

RUN \
 \
    apt-get -y update \
 && apt-get -y upgrade \
 \
 # ========================================================================
 #
 #  install requested software
 #
 # ========================================================================
 \
 && case ${DSTACK} in \
     "BASE")    /tmp/base.sh          ;; \
     "DEV")     /tmp/x11-dev.sh       ;; \
     "PERL")    /tmp/perl.sh          ;; \
     "SURFACE") /tmp/x11-surface.sh   ;; \
     "X11")     /tmp/x11-base.sh      ;; \
     *)       echo "UNKNOWN STACK NAME: ${DSTACK}"; exit 1 ;; \
    esac \
 \
 && apt-get clean all \
 \
 # ========================================================================
 #
 #   Re-load libraries if LIB_INSTALL is 1, otherwise skip it
 #		Sould only be set to 1 if building dbase.
 #
 # ========================================================================
 && if test "${LMSLIB_INST}" = "1"; then /tmp/install_library.sh; fi \
 \
 # ========================================================================
 #
 #   setup libraries and applications to run
 #
 # ========================================================================
 && mkdir -p /usr/local/bin \
 && mkdir -p /usr/bin/lms \
 && mkdir -p /usr/local/lib/lms/tests \
 && mkdir -p /etc/lms \
 \
 && chmod +x /my_init \
 && chmod +x /my_service \
 \
 && cp /usr/bin/lms/lms-setup.sh /etc/my_runonce/lms-setup.sh \
 && chmod +x /etc/my_runonce/*.sh \
 && chmod +x /etc/lms/*.sh \
 \
 && chmod +x /usr/local/bin/*.* \
 && chmod +x /usr/local/bin/* \
 \
 && chmod +x /usr/bin/lms/*.* \
 && chmod +x /usr/bin/lms/* \
 \
 && chmod +x /usr/local/lib/lms/tests/*.sh \
 \
 # ========================================================================
 #
 #   register the installed software
 #
 # ========================================================================
 && dpkg-query -W > /etc/lms/.installedPackages \
 && printf "%s (%s), %s @ %s\n" ${LMS_DOCKER} ${LMS_FROM} `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt  

# =========================================================================

VOLUME /conf
VOLUME /usrlocal

# =========================================================================

WORKDIR /root

# =========================================================================

ENTRYPOINT ["/my_init", "--quiet"]

