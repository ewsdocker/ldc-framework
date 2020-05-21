# ========================================================================================
# ========================================================================================
#
#    Dockerfile
#      Dockerfile for ldc-core:dcore.
#
# ========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2017-2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ldc-core
# @subpackage Dockerfile.dcore
#
# ========================================================================================
#
#	Copyright © 2017-2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-core.
#
#   ewsdocker/ldc-core is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-core is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-core.  If not, see 
#   <http://www.gnu.org/licenses/>.
#
# ========================================================================================
# ========================================================================================

ARG FROM_REPO=""
ARG FROM_PARENT="debian"
ARG FROM_VERS="9.12"
ARG FROM_EXT=""
ARG FROM_EXT_MOD=""

FROM ${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}

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
ARG BUILD_BASE

# ========================================================================================

ARG RUN_APP

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
    LRUN_APP=${RUN_APP:-"/bin/bash"} \
    \
    LMS_DAEMON=${BUILD_DAEMON:-"1"} \
    LMS_TEMPLATE=${BUILD_TEMPLATE:-"daemon"} \
    \
    LMSOPT_TIMEOUT=${OPT_TIMEOUT:-"30"} \
    LMSOPT_QUIET=${OPT_QUIET:-"1"} \
    LMSOPT_DEBUG=${OPT_DEBUG:-"0"} \
    \
    LMS_BASE=${BUILD_BASE:-"/usr/local"} \
    \
    LMS_HOME= \
    LMS_CONF= \
    \
    LMS_REGISTRY=${BUILD_REGISTRY:-""} \
    LMS_REPO=${BUILD_REPO:-"ewsdocker"} \
    \
    LMS_FROM="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    LMS_PARENT="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    \
    LMS_NAME=${BUILD_NAME:-"ldc-core"} \
    LMS_VERSION=${BUILD_VERSION:-"dcore"} \
    LMS_VERS_X=${BUILD_VERS_EXT:-"-0.1.0"} \
    LMS_VERS_MOD=${BUILD_EXT_MOD} 

ENV LMS_VERS_EXT="${LMS_VERS_X}${LMS_VERS_MOD}" \
    LMS_PACKAGE="${LMS_PARENT}, Debian 9.11"

ENV \
    LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}" 

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}" 

# ========================================================================================
# ========================================================================================
#
#   install required scripts
#
# ========================================================================================
# ========================================================================================

COPY setup/. /tmp/

# ========================================================================================
# ========================================================================================
#
#   Build image
#
# ========================================================================================
# ========================================================================================

RUN chmod +x /tmp/*.sh \
 \
 # ========================================================================
 #
 #   re-route system initctrl to /bin/true
 #
 # ========================================================================
 && dpkg-divert --local --rename --add /sbin/initctl \
 && ln -sf /bin/true /sbin/initctl \
 \
 # ========================================================================
 #
 #  install requested software
 #
 # ========================================================================
 \
 && case ${DSTACK} in \
     "CORE")  /tmp/core.sh   ;; \
     *)       echo "UNKNOWN STACK NAME: ${DSTACK}"; exit 1 ;; \
    esac \
 \
 && apt-get clean all \
 \
 # ========================================================================
 #
 #   generate locale for en_US
 #
 # ========================================================================
 \
 && locale-gen en_US \
 && update-locale LANG=C.UTF-8 LC_MESSAGES=POSIX 

# =========================================================================

COPY scripts/etc/. /etc/
COPY scripts/var/. /var/
COPY scripts/my* /

# =========================================================================

RUN \
 # ========================================================================
 #
 #   fixes for ubuntu/debian
 #
 # ========================================================================
 \
    mkdir -p /etc/workaround-docker-2267/ \
 && mkdir -p /etc/container_environment \
 && chmod 775 /etc/my_runonce/*.sh \
 && sed -i -E 's/^(\s*)system\(\);/\1unix-stream("\/dev\/log");/' /etc/syslog-ng/syslog-ng.conf \
 \
 # ========================================================================
 #
 #   setup libraries and applications to run
 #
 # ========================================================================
 \
 && chmod +x /my_init \
 && chmod +x /my_service \
 \
 # ========================================================================
 #
 #   register the installed software
 #
 # ========================================================================
 \
 && echo "debian:$(cat /etc/debian_version)" >  /etc/ewsdocker-builds.txt \
 && dpkg-query -W > /etc/lms/.installedPackages \
 && printf "%s (%s), %s @ %s\n" ${LMS_DOCKER} ${LMS_FROM} `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt

# =========================================================================

VOLUME /conf
VOLUME /usrlocal

# =========================================================================

WORKDIR /root

# =========================================================================

ENTRYPOINT ["/my_init", "--quiet"]
