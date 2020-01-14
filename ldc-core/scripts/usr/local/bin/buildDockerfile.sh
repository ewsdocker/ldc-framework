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
ARG FROM_VERS="9.8"
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

ARG LIB_NAME
ARG LIB_VERSION
ARG LIB_HOST

# ========================================================================================

ARG RUN_APP

# ========================================================================================

ARG PATCH_TYPE
ARG PATCH_FILE
ARG PATCH_HOST

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

ARG OPT_DEBUG
ARG OPT_QUIET
ARG OPT_TIMEOUT

# ========================================================================================
# ========================================================================================
#
# https://github.com/ewsdocker/ldc-utilities/releases/download/ldc-utilities-0.1.4/ldc-library-0.1.4.tar.gz
#
# ========================================================================================
# ========================================================================================

ENV \
    LRUN_APP=${RUN_APP:-"/bin/bash"} \
    \
    LMS_DAEMON=${BUILD_DAEMON:-"1"} \
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
    LMS_NAME=${BUILD_NAME:-"ldc-core"} \
    LMS_VERSION=${BUILD_VERSION:-"dcore"} \
    LMS_VERS_EXT=${BUILD_VERS_EXT:-"-0.1.0"}${BUILD_EXT_MOD} 

ENV \
    LMS_FROM="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    LMS_PARENT="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}" \
    \
    LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}" 

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}" 

# ========================================================================================

ENV LMSLIB_VERS=${LIB_VERSION:-"0.1.4"} 

ENV LMSLIB_HOST=${LIB_HOST:-"https://github.com/ewsdocker/ldc-utilities/releases/download/ldc-library-${LMSLIB_VERS}"} \
    LMSLIB_NAME=${LIB_NAME:-"ldc-library"}

ENV LMSLIB_PKG=${LMSLIB_NAME}-${LMSLIB_VERS}.tar.gz 

ENV LMSLIB_URL=${LMSLIB_HOST}/${LMSLIB_PKG}

# ========================================================================================

ENV LP_TYPE=${PATCH_TYPE} \
    LP_FILE=${PATCH_FILE} \
    LP_HOST=${PATCH_HOST} 

ENV LP_URL=${LP_HOST}/${LP_FILE}

# ========================================================================================
# ========================================================================================
#
#   install required scripts
#
# ========================================================================================
# ========================================================================================

RUN \
 # ========================================================================
 #
 #   re-route system initctrl to /bin/true
 #
 # ========================================================================
    dpkg-divert --local --rename --add /sbin/initctl \
 && ln -sf /bin/true /sbin/initctl \
 \
 # ========================================================================
 #
 #  setup apt
 #
 # ========================================================================
 \
 && echo 'APT::Install-Recommends 0;' >> /etc/apt/apt.conf.d/01norecommends \
 && echo 'APT::Install-Suggests 0;' >> /etc/apt/apt.conf.d/01norecommends \
 && sed -i 's/^#\s*\(deb.*multiverse\)$/\1/g' /etc/apt/sources.list \
 \
 # ========================================================================
 #
 #  update the apt cache, install package upgrades and install required software
 #
 # ========================================================================
 \
 && apt-get -y update \
 && apt-get -y upgrade \
 && apt-get -y install \
            apt-transport-https \
            bash-doc \
            bash-completion \
            binutils \
            bzip2 \
            cron \
            curl \
            dbus \
            file \
            git \
            gnupg2 \
            libcurl3-gnutls \
            locales \
            logrotate \
            lsb-release \
            nano \
            procps \
            psmisc \
            software-properties-common \
            sudo \
            supervisor \
            syslog-ng \
            syslog-ng-core \
            unzip \
            wget \
            zip \
 && apt-get -y dist-upgrade \
 && apt-get clean all \
 \
 # ========================================================================
 #
 #   generate locale for en_US
 #
 # ========================================================================
 \
 && locale-gen en_US \
 && update-locale LANG=C.UTF-8 LC_MESSAGES=POSIX \
 \
 # ========================================================================
 #
 #   fixes for ubuntu/debian
 #
 # ========================================================================
 \
 && mkdir -p /etc/workaround-docker-2267/ \
 && mkdir -p /etc/container_environment \
 && mkdir -p /etc/my_runonce \
 && mkdir -p /etc/my_runalways \
 && sed -i -E 's/^(\s*)system\(\);/\1unix-stream("\/dev\/log");/' /etc/syslog-ng/syslog-ng.conf 
  
# =========================================================================

COPY scripts/. /

# =========================================================================

RUN \
 # ========================================================================
 #
 #   download and install ldc-library
 #
 # ========================================================================
    L_PWD=$PWD \
 && cd / \
 && wget "${LMSLIB_URL}" \ 
 && tar -xvf "${LMSLIB_PKG}" \ 
 && rm "${LMSLIB_PKG}" \ 
 && cd $L_PWD \
 \
 # ========================================================================
 #
 #   setup libraries and applications to run
 #
 # ========================================================================
 \
 && mkdir -p /usr/local/bin \
 && mkdir -p /usr/bin/lms \
 \
 && cp /usr/bin/lms/lms-setup.sh /etc/my_runonce \
 && chmod +x /etc/my_runonce/*.sh \
 \
 && chmod +x /etc/lms/*.sh \
 \
 && chmod +x /usr/local/bin/*.* \
 && chmod +x /usr/local/bin/* \
 \
 && chmod +x /usr/bin/lms/*.* \
 && chmod +x /usr/bin/lms/* \
 \
 && chmod +x /my_init \
 && chmod +x /my_service \
 \
 && ln -s /usr/bin/lms/lms-setup.sh /usr/bin/lms-setup \
 && ln -s /usr/bin/lms/lms-version.sh /usr/bin/lms-version \
 && ln -s /usr/bin/lms/install-flashplayer.sh /usr/bin/lms-install-flash \
 \
 # ========================================================================
 #
 #   register the installed software
 #
 # ========================================================================
 \
 && echo "debian:$(cat /etc/debian_version)" >  /etc/ewsdocker-builds.txt \
 && printf "%s (%s), %s @ %s\n" ${LMS_DOCKER} ${LMS_FROM} `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt  

# =========================================================================

VOLUME /conf
VOLUME /usrlocal

# =========================================================================

WORKDIR /root

# =========================================================================

ENTRYPOINT ["/my_init", "--quiet"]
