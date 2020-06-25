# =========================================================================
# =========================================================================
#
#	Dockerfile
#	  Dockerfile for ldc-ck-library.
#
# =========================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2017-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-ck-library
# @subpackage Dockerfile
#
# =========================================================================
#
#	Copyright © 2017-2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-ck-library.
#
#   ewsdocker/ldc-ck-library is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-ck-library is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-ck-library.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# =========================================================================
# =========================================================================

ARG FROM_REPO="ewsdocker"
ARG FROM_PARENT="ldc-stack"
ARG FROM_VERS="dqt4-x11"
ARG FROM_EXT="-0.1.0"
ARG FROM_EXT_MOD="-b3"

FROM ${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}

MAINTAINER Jay Wheeler <EarthWalkSoftware@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

# =========================================================================

ARG FROM_REPO
ARG FROM_PARENT
ARG FROM_VERS
ARG FROM_EXT
ARG FROM_EXT_MOD

# =========================================================================

ARG RUN_APP

# =========================================================================

ARG BUILD_TEMPLATE
ARG BUILD_DAEMON

ARG BUILD_CATEGORIES
ARG BUILD_DESKTOP
ARG BUILD_ICON

ARG BUILD_REGISTRY
ARG BUILD_REPO

ARG BUILD_NAME 
ARG BUILD_VERSION
ARG BUILD_VERS_EXT
ARG BUILD_EXT_MOD

ARG BUILD_PKG
ARG BUILD_BASE

# =========================================================================

ARG LIB_HOST
ARG LIB_NAME
ARG LIB_VERSION
ARG LIB_VERS_MOD
ARG LIB_INSTALL

# =========================================================================

ARG QT_VER

# =========================================================================

ARG KAPTAIN_HOST
ARG KAPTAIN_VERS

# =========================================================================

ARG DNAME

# ========================================================================================

ARG OPT_DEBUG
ARG OPT_TIMEOUT
ARG OPT_QUIET

# ========================================================================================

ARG CKLIB_INSTALL

ARG CKLIB_HOST
ARG CKLIB_NAME
ARG CKLIB_VERS
ARG CKLIB_VERSX
ARG CKLIB_RELEASE

ARG CKLIB_DIR
ARG CKLIB_DEST

# ========================================================================================
# ========================================================================================
#
# https://github.com/ewsdocker/ldc-ckaptain/releases/download/ldc-ckaptain-0.1.0/ckaptain-lib-0.1.0-b3-v.0.tar.gz
#
# ========================================================================================
#
#   CKLIB_NAME = name of the tarball (w/o version) to create (defaults to "ck-library")
#   CKLIB_DEST = path to the archive storage directory (defaults to "/repo")
#
#   CKLIB_DIR  = name of the target directory (defaults to "usr")
#
# ========================================================================================
# ========================================================================================

ENV CKL_INST=${CKLIB_INSTALL:-"0"} \
    \
    CKL_HOST=${CKLIB_HOST:-"https://github.com/ewsdocker/ldc-ckaptain/releases/download/ldc-ckaptain-0.1.0"} \
    CKL_NAME=${CKLIB_NAME:-"ckaptain-lib"} \
    CKL_VERS=${CKLIB_VERS:-"0.1.0"} \
    CKL_VERSX=${CKLIB_VERSX:-""} \
    CKL_REL=${CKLIB_RELEASE:-"v.0"} \
    CKL_DEST=${CKLIB_DEST:-"/repo"} \
    \
    CKL_DIR=${CKLIB_DIR:-"/ckaptain"}

	# ====================================================================================

ENV CKL_TARNAME="${CKL_NAME}-${CKL_VERS}${CKL_VERSX}-${CKL_REL}" 
ENV CKL_TARBALL="${CKL_TARNAME}.tar" 
ENV CKL_TARGZIP="${CKL_TARBALL}.gz" 

# ========================================================================================

ENV DSTACK=${DNAME:-"NONE"}

# ========================================================================================

ENV QTVER=${QT_VER:-"QT4"}

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
#
#    
#
# =========================================================================
# =========================================================================

ENV \
    DSTACK=${DNAME:-"NONE"} \
    \
    LRUN_APP=${RUN_APP:-"bash"} \
    \
    LMS_DAEMON=${BUILD_DAEMON:-"0"} \
    LMS_TEMPLATE=${BUILD_TEMPLATE:-"gui"} \
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
    LMS_NAME=${BUILD_NAME:-"ldc-ckaptain"} \
    LMS_VERSION=${BUILD_VERSION:-"ckaptain"} \
    LMS_VERS_EXT=${BUILD_VERS_EXT:-"-0.1.0"}${BUILD_EXT_MOD} \
    \
    LMS_CATEGORIES=${BUILD_CATEGORIES:-"Development"} \
    LMS_DESKTOP=${BUILD_DESKTOP:-"CKaptain"} \
    LMS_ICON=${BUILD_ICON:-"default.png"} \
    \
    LMS_FROM="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    LMS_PARENT="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}"

ENV LMS_PACKAGE="${LMS_PARENT}, Kaptain v. ${KAPTAIN_VERS:-"0.8"}"

ENV LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}"

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}"

# =========================================================================
# =========================================================================
#
# https://github.com/ewsdocker/ldc-utilities/releases/download/ldc-utilities-0.1.6/ldc-library-0.1.6-b3.tar.gz
#
# =========================================================================
# =========================================================================

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

VOLUME /pictures
VOLUME /workspace

# =========================================================================
# =========================================================================

COPY scripts/. /
COPY setup/. /lmstmp/
COPY ckaptain-lib/. /ckaptain-lib/

# =========================================================================
# =========================================================================

RUN chmod +x /lmstmp/*.sh \
 && chmod +x /ckaptain-lib/bin/*.sh \
 \
 # =======================================================================================
 #
 #    Refresh the APT repository cache
 #
 # =======================================================================================
 \
 && apt-get -y update \
 \
 # =======================================================================================
 #
 #    Perform the proper setup depending upon the DSTACK setting
 #
 # =======================================================================================
 \
 && case ${DSTACK} in \
     "CK-LIB")    /lmstmp/ck-library.sh ;; \
     *)           echo "unknown selection DNAME=\"${DSTACK}\""; exit 1 ;; \
    esac \
 \
 # ========================================================================
 #
 #   setup folders and permissions
 #
 # ========================================================================
 \
 && mkdir -p /usr/local/bin \
 && mkdir -p /usr/local/lib/lms \
 \
 && chmod +x /etc/lms/*.sh \
 && chmod +x /my_init \
 && chmod +x /my_service \
 && chmod +x /usr/bin/lms/*.* \
 && chmod +x /usr/bin/lms/* \
 && chmod +x /usr/local/bin/*.* \
 && chmod +x /usr/local/bin/* \
 && chmod +x /usr/local/lib/lms/*.* \
 \
 && ln -s /usr/local/lib/bin/ck-archive.sh /usr/local/bin/ck-archive \
 \
 # =========================================================================
 #
 #   download and install ldc-library
 #
 # =========================================================================
 \
 && if test "${LMSLIB_INST}" = "1"; then cd / ; wget "${LMSLIB_URL}"; tar -xvf "${LMSLIB_PKG}"; rm "${LMSLIB_PKG}"; cd /root; fi \
 \
 # ========================================================================
 #
 #   record the installed software packages
 #
 # ========================================================================
 \
 && dpkg-query -W > /etc/lms/.installedPackages \
 \
 # =========================================================================
 #
 #   register the build with /etc/ewsdocker-builds.txt
 #
 # =========================================================================
 \
 && printf "${LMS_DOCKER} (${LMS_PARENT}), %s @ %s\n" `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt \
 \
 # =========================================================================
 #
 #   clean-up apt and temporary folder(s)
 #
 # =========================================================================
 \
 && apt-get clean all \
 && rm -Rf /lmstmp 

# =========================================================================

ENTRYPOINT [ "/etc/lms/entrypoint.sh" ]
