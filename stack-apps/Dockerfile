# ========================================================================================
# ========================================================================================
#
#	Dockerfile
#	  Dockerfile for ldc-stack-apps in a Docker container.
#
# ========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019-2020. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-stack-apps
# @subpackage Dockerfile
#
# ========================================================================================
#
#	Copyright © 2019-2020. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-stack-apps.
#
#   ewsdocker/ldc-stack-apps is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-stack-apps is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-stack-apps.  If not, see <http://www.gnu.org/licenses/>.
#
# ========================================================================================
# ========================================================================================

ARG FROM_REPO="ewsdocker"
ARG FROM_PARENT="ldc-stack"
ARG FROM_VERS="dcc"
ARG FROM_EXT="-0.1.0"
ARG FROM_EXT_MOD="-b3"

FROM ${FROM_REPO}/${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}

# ========================================================================================
# ========================================================================================

   ARG FROM_PARENT
   ARG FROM_REPO
   ARG FROM_VERS
   ARG FROM_EXT
   ARG FROM_EXT_MOD

# ========================================================================================

   ARG DNAME

# =========================================================================

ARG JDK_RELEASE 
ARG JDK_VERS 
ARG JDK_HOST
ARG JDK_TYPE

ARG JDK_BUILD

# =========================================================================

ARG PERL_VER
ARG PERL_REL
ARG PERL_HOST

# ========================================================================================

ARG PHP_VER
ARG PHP_COMP

# ========================================================================================

ARG FORTRAN_VER

# =========================================================================

ARG NODEJS_HOST
ARG NODEJS_VER

# ========================================================================================

   ARG LIB_HOST
   ARG LIB_NAME
   ARG LIB_VERSION
   ARG LIB_VERS_MOD
   ARG LIB_INSTALL

# ========================================================================================

   ARG BUILD_CATEGORIES
   ARG BUILD_DESKTOP
   ARG BUILD_ICON

   # =====================================================================================

   ARG BUILD_REGISTRY
   ARG BUILD_REPO

   # =====================================================================================

   ARG BUILD_NAME 
   ARG BUILD_VERSION
   ARG BUILD_VERS_EXT
   ARG BUILD_EXT_MOD

   # =====================================================================================

   ARG BUILD_TEMPLATE
   ARG BUILD_DAEMON

# ========================================================================================

   ARG OPT_DEBUG
   ARG OPT_TIMEOUT
   ARG OPT_QUIET

# =========================================================================
# =========================================================================

ENV FOR_VER=${FORTRAN_VER:-"6"} 

# =========================================================================
#
# https://nodejs.org/dist/v14.8.0/node-v14.8.0-linux-x64.tar.xz
#
# =========================================================================

ENV NJS_VER=${NODEJS_VER:-"14"} \
    NJS_HOST=${NODEJS_HOST:-"https://deb.nodesource.com"} \
    NJS_DIR="NodeJS" 

ENV NJS_NAME="setup_${NJS_VER}.x" 
ENV NJS_URL=${NJS_HOST}/${NJS_NAME}  

# =========================================================================

ENV LANG_PHPVER=${PHP_VER:-"5.6"} \
    LANG_PHPCOMP=${PHP_COMP:-"1"}

# ========================================================================================
#
#  Perl 5.30.1 source:
#     https://www.cpan.org/src/5.0/perl-5.30.1.tar.gz
#
# ========================================================================================

ENV PRL_BUILD=${PERL_BUILD:-"0"} \
    PRL_REL=${PERL_REL:-"5.0"} \
    PRL_VER=${PERL_VER:-"5.30.1"} 

ENV PRL_DIR=${PERL_DIR:-"/usr/local/share/perl-${PRL_VER}"} 

ENV PRL_NAME="perl-${PRL_VER}" \
    PRL_HOST=${PERL_HOST:-"https://www.cpan.org/src/${PRL_REL}"} 

ENV PRL_PKG="${PRL_NAME}.tar.gz"

ENV PRL_URL=${PRL_HOST}/${PRL_PKG}

# =========================================================================

ENV OJDK_RELEASE=${JDK_RELEASE:-"jdk11"} \
    OJDK_VERS=${JDK_VERS:-"13.0.1"} \
    OJDK_TYPE=${JDK_TYPE:-"jdk13"}

ENV OJDK_PKG="openjdk-${OJDK_VERS}_linux-x64_bin.tar.gz" 

ENV OJDK_HOST=${JDK_HOST:-"https://download.java.net/java/GA/jdk11/9/GPL/"}
ENV OJDK_URL="${OJDK_HOST}/${OJDK_PKG}" \
    \
    LMS_JDK=${JDK_BUILD:-"jdk11"} 
 
# ========================================================================================
# ========================================================================================

ENV DSTACK=${DNAME:-""}

ENV \
    LMS_DAEMON=${BUILD_DAEMON:-"1"} \
    LMS_TEMPLATE=${BUILD_TEMPLATE:-"daemon"} \
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
    LMS_CATEGORIES=${BUILD_CATEGORIES:-"Development"} \
    LMS_DESKTOP=${BUILD_DESKTOP:-"ldc-stack-apps"} \
    LMS_ICON=${BUILD_ICON:-"default.png"} \
    \
    LMS_FROM="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    LMS_PARENT="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    \
    LMS_NAME=${BUILD_NAME:-"ldc-stack-apps"} \
    LMS_VERSION=${BUILD_VERSION:-"dstack-apps"} \
    LMS_VERS_X=${BUILD_VERS_EXT:-"-0.1.0"} \
    LMS_VERS_MOD=${BUILD_EXT_MOD:-"-b3"} 

ENV LMS_VERS_EXT="${LMS_VERS_X}${LMS_VERS_MOD}" 

ENV LMS_RUN_NAME="${LMS_NAME}-${LMS_VERSION}${LMS_VERS_EXT}" \
    LMS_DOCKER_NAME="${LMS_NAME}:${LMS_VERSION}${LMS_VERS_EXT}"

ENV LMS_DOCKER="${LMS_REPO}/${LMS_DOCKER_NAME}" \
    LMS_PACKAGE="${LMS_PARENT}, ${LMS_DOCKER_NAME}"

# ========================================================================================
# ========================================================================================
#
#	ldc-library
#
# https://github.com/ewsdocker/ldc-utilities/releases/download/ldc-utilities-0.1.6/ldc-library-0.1.6-b3.tar.gz
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

# ========================================================================================
# ========================================================================================

VOLUME /conf
VOLUME /usrlocal

# ========================================================================================
# ========================================================================================

#COPY scripts/. /
COPY setup/. /tmp/

# ========================================================================================
# ========================================================================================

RUN \
    chmod +x /tmp/*.sh \
 \
 # =======================================================================================
 #
 #    build the apt repo cache
 #
 # =======================================================================================
 && apt-get -y update \
 && apt-get -y upgrade \
 \
 # =======================================================================================
 #
 #    install common ("global") dependecies
 #
 # =======================================================================================
 \
 && case ${DSTACK} in \
     "CPP")        /tmp/cpp.sh      ;; \
     "FORT")       /tmp/gfortran.sh ;; \
     "JDK")        /tmp/jdk.sh      ;; \
     "PERL")       /tmp/perl.sh     ;; \
     "PHP")        /tmp/php5.6.sh   ;; \
     "NJS")        /tmp/njs.sh      ;; \
     "QT")         /tmp/qt.sh       ;; \
     *)            echo "unknown stack"; exit 1 ;; \
    esac \
 \
 && apt-get clean all \
 \
 # =======================================================================================
 #
 #   download and install ldc-library, if requested
 #
 # =======================================================================================
 \
 && if test "${LMSLIB_INST}" = "1"; then install_library.sh; fi \
 \
 # =======================================================================================
 #
 #   setup libraries and applications to run
 #
 # =======================================================================================
 \
 && mkdir -p /usr/local/bin \
 && mkdir -p /usr/bin/lms \
 \
 && chmod +x /usr/local/bin/*.* \
 && chmod +x /usr/local/bin/* \
 && chmod +x /usr/bin/lms/*.* \
 && chmod +x /usr/bin/lms/* \
 && chmod +x /etc/lms/*.sh \
 && chmod +x /my_init \
 && chmod +x /my_service \
 \
 # =======================================================================================
 #
 #   register the build chain with /etc/ewsdocker-builds.txt
 #
 # =======================================================================================
 \
 && printf "${LMS_DOCKER} (${LMS_FROM}), %s @ %s\n" `date '+%Y-%m-%d'` `date '+%H:%M:%S'` >> /etc/ewsdocker-builds.txt 

# ========================================================================================
# ========================================================================================

ENTRYPOINT ["/my_init", "--quiet"]
