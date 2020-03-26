# =========================================================================
# =========================================================================
#
#	Dockerfile
#	  Dockerfile for Libre Office in a Debian container.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-dialog
# @subpackage Dockerfile.ddialog
#
# =========================================================================
#
#	Copyright © 2019-2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-dialog.
#
#   ewsdocker/ldc-dialog is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-dialog is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-dialog.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

ARG FROM_REPO="ewsdocker" 
ARG FROM_PARENT="ldc-stack" 
ARG FROM_VERS="dgtk3-x11" 
ARG FROM_EXT="-0.1.0" 
ARG FROM_EXT_MOD="" 

FROM ${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}

MAINTAINER Jay Wheeler <EarthWalkSoftware@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

# ========================================================================================
# =========================================================================

ARG FROM_REPO
ARG FROM_PARENT
ARG FROM_VERS
ARG FROM_EXT
ARG FROM_EXT_MOD

# =========================================================================

ARG KAPTAIN_HOST
ARG KAPTAIN_VERS

# =========================================================================

ARG RUN_APP

# ========================================================================================

ARG DIALOG

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

ARG BUILD_PKG
ARG BUILD_BASE

# ========================================================================================

ARG BUILD_TEMPLATE
ARG BUILD_DAEMON

# ========================================================================================

ARG BUILD_CATEGORIES
ARG BUILD_DESKTOP
ARG BUILD_ICON

# ========================================================================================

ARG OPT_DEBUG
ARG OPT_TIMEOUT
ARG OPT_QUIET

# ========================================================================================
# ========================================================================================

ENV LMS_DLG=${DIALOG:-"NONE"} \
    \
    LRUN_APP=${RUN_APP:-"dialog"} \
    \
    LMS_DAEMON=${BUILD_DAEMON:-"0"} \
    LMS_TEMPLATE=${BUILD_TEMPLATE:-"run"} \
    \
    LMSOPT_TIMEOUT=${OPT_TIMEOUT:-"30"} \
    LMSOPT_QUIET=${OPT_QUIET:-"0"} \
    LMSOPT_DEBUG=${OPT_DEBUG:-"0"} \
    \
    LMS_BASE=${BUILD_BASE:-"/usr/local"} \
    \
    LMS_HOME= \
    LMS_CONF= \
    \
    LMS_CATEGORIES=${BUILD_CATEGORIES:-"Development"} \
    LMS_DESKTOP=${BUILD_DESKTOP:-"Dialog"} \
    LMS_ICON=${BUILD_ICON:-"default.png"} \
    \
    LMS_FROM="${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    LMS_PARENT="${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    \
    LMS_REGISTRY=${BUILD_REGISTRY:-""} \
    LMS_REPO=${BUILD_REPO:-"ewsdocker"} \
    \
    LMS_NAME=${BUILD_NAME:-"ldc-dialog"} \
    LMS_VERSION=${BUILD_VERSION:-"ddialog"} \
    LMS_VERS_X=${BUILD_VERS_EXT:-"-0.1.0"} \
    LMS_VERS_MOD=${BUILD_EXT_MOD:-""} \
    \
    LMS_BLD_PKG=${BUILD_PKG}

ENV LMS_VERS_EXT="${LMS_VERS_X}${LMS_VERS_MOD}" \
    LMS_PACKAGE="${LMS_PARENT}, ${LMS_BLD_PKG}"

ENV LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}"

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}"

# ========================================================================================
# ========================================================================================
#
# https://github.com/ewsdocker/ldc-utilities/releases/download/ldc-utilities-0.1.6/ldc-library-0.1.6-b1.tar.gz
#
# ========================================================================================
# ========================================================================================

ENV LMSLIB_INST=${LIB_INSTALL:-"0"} \
    LMSLIB_NAME=${LIB_NAME:-"ldc-library"} \
    LMSLIB_VERS=${LIB_VERSION:-"0.1.6"} \
    LMSLIB_VERS_MOD=${LIB_VERS_MOD:-""}

ENV LMSLIB_HOST=${LIB_HOST:-"https://github.com/ewsdocker/ldc-utilities/releases/download/ldc-utilities-${LMSLIB_VERS}"}

ENV LMSLIB_VERSION="${LMSLIB_VERS}${LMSLIB_VERS_MOD}"
ENV LMSLIB_FULLNAME="${LMSLIB_NAME}-${LMSLIB_VERSION}"

ENV LMSLIB_PKG=${LMSLIB_FULLNAME}.tar.gz 

ENV LMSLIB_URL=${LMSLIB_HOST}/${LMSLIB_PKG}

# =========================================================================
# =========================================================================
#
#	kaptain
#      https://github.com/mviereck/kaptain/raw/master
#
# =========================================================================
# =========================================================================

ENV KAPT_HOST=${KAPTAIN_HOST:-"https://github.com/mviereck/kaptain/raw/master"} \
    KAPT_DIR=kaptain \
    KAPT_VERS=${KAPTAIN_VERS:-"0.8"}

ENV KAPT_PKG=kaptain_${KAPT_VERS}_amd64_debian.deb 

ENV KAPT_URL=${KAPT_HOST}/${KAPT_PKG}

# =========================================================================
# =========================================================================

VOLUME /source
VOLUME /workspace

# =========================================================================
# =========================================================================

COPY scripts/. /
COPY setup/. /tmp/

# =========================================================================
# =========================================================================

RUN chmod +x /tmp/*.sh \
 && echo "" \
 && echo "**************************************************" \
 && echo "**************************************************" \
 && echo "" \
 && echo "Building: ${LMS_DESKTOP} - ${LMS_DOCKER}" \
 && echo "From:     ${LMS_PACKAGE}" \
 && echo "" \
 && echo "**************************************************" \
 && echo "**************************************************" \
 && echo "" \
 \
 # =========================================================================
 #
 #    Update/Upgrade
 #
 # =========================================================================
 && apt-get -y update \
 \
 # =========================================================================
 #
 #    Install application
 #
 # =========================================================================
 \
 && case "${LMS_DLG}" in \
      "DIALOG")   /tmp/dialog.sh   ;; \
      "KAPTAIN")  /tmp/kaptain.sh  ;; \
      "WHIP")     /tmp/whiptail.sh ;; \
      "YAD")      /tmp/yad.sh      ;; \
      "ZENITY")   /tmp/zenity.sh   ;; \
      *) echo "Unknow Dialog: ${LMS_DLG}" ; exit 1 ;; \
    esac \
 \
 # =========================================================================
 #
 #   download and install ldc-library
 #
 # =========================================================================
 \
 && if test "${LMSLIB_INST}" = "1"; then echo ""; echo "   **** Installing ${LMSLIB_PKG} ****"; echo ""; fi \
 && if test "${LMSLIB_INST}" = "1"; then cd / ; wget "${LMSLIB_URL}"; tar -xvf "${LMSLIB_PKG}"; rm "${LMSLIB_PKG}"; cd /root; fi \
 \
 # ========================================================================
 #
 #   setup folders and permissions
 #
 # ========================================================================
 && mkdir -p /usr/local/bin \
 \
 && chmod +x /etc/lms/*.sh \
 && chmod +x /my_init \
 && chmod +x /my_service \
 && chmod +x /usr/bin/lms/*.* \
 && chmod +x /usr/bin/lms/* \
 && chmod +x /usr/local/bin/*.* \
 && chmod +x /usr/local/bin/* \
 \
 # =========================================================================
 #
 #   register the build with /etc/ewsdocker-builds.txt
 #
 # =========================================================================
 \
 && printf "${LMS_DOCKER} (${LMS_PARENT}), %s @ %s\n" `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt  \
 \
 # =========================================================================
 #
 #   Exit message on the display
 #
 # =========================================================================
 && echo "" \
 && echo "**************************************************" \
 && echo "**************************************************" \
 && echo "" \
 && echo "Exiting: ${LMS_DESKTOP} - ${LMS_DOCKER}" \
 && echo "From:     ${LMS_PACKAGE}" \
 && echo "" \
 && echo "**************************************************" \
 && echo "**************************************************" \
 && echo "" 
 
# =========================================================================

ENTRYPOINT [ "/etc/lms/entrypoint.sh" ]
