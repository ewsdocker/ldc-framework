# ========================================================================================
# ========================================================================================
#
#	Dockerfile
#	  Dockerfile for ldc-langdev:dlangdev in a Docker container.
#
# ========================================================================================
#
# @author Jay Wheeler.
# @version 0.1.0
# @copyright © 2019. EarthWalk Software.
# @license Licensed under the GNU General Public License, GPL-3.0-or-later.
# @package ewsdocker/ldc-langdev
# @subpackage Dockerfile.dlangdev
#
# ========================================================================================
#
#	Copyright © 2019. EarthWalk Software
#	Licensed under the GNU General Public License, GPL-3.0-or-later.
#
#   This file is part of ewsdocker/ldc-langdev.
#
#   ewsdocker/ldc-langdev is free software: you can redistribute 
#   it and/or modify it under the terms of the GNU General Public License 
#   as published by the Free Software Foundation, either version 3 of the 
#   License, or (at your option) any later version.
#
#   ewsdocker/ldc-langdev is distributed in the hope that it will 
#   be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
#   of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with ewsdocker/ldc-langdev.  If not, see <http://www.gnu.org/licenses/>.
#
# ========================================================================================
# ========================================================================================

ARG FROM_REPO="ewsdocker"
ARG FROM_PARENT="ldc-langdev"
ARG FROM_VERS="dcc-gui"
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

# ========================================================================================

ARG CC_VER
ARG CC_GUI
ARG CC_MULTI

# =========================================================================

ARG GTK_COMMON
ARG GTK_VER

# =========================================================================

ARG JDK_RELEASE 
ARG JDK_VERS 
ARG JDK_HOST
ARG JDK_TYPE

ARG JDK_BUILD

# =========================================================================

ARG NODEJS_HOST
ARG NODEJS_VER

# =========================================================================

ARG PERL_VER
ARG PERL_REL
ARG PERL_HOST

# ========================================================================================

ARG PHP_VER
ARG PHP_COMP

# =========================================================================

ARG QT_VER

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

ENV DSTACK=${DNAME:-"CPP"}

# =========================================================================
#
#	C++
#		Gnu gcc/g++ vers 6
#
# =========================================================================

ENV CPP_VER=${CC_VER:-"6"} \ 
    CPP_GUI=${CC_GUI:-"0"}

# ========================================================================================

ENV GTKVER=${GTK_VER:-"GTK3"}

# =========================================================================
#
#	Java - OpenJDK
#		JDK13
#			https://download.java.net/java/GA/jdk13.0.1/cec27d702aa74d5a8630c65ae61e4305/9/GPL/openjdk-13.0.1_linux-x64_bin.tar.gz
#
#   Java - ojdkbuild
#           https://github.com/ojdkbuild/contrib_jdk13u-ci/releases/download/jdk-13.0.1%2B9/jdk-13.0.1-ojdkbuild-linux-x64.zip
#
# =========================================================================

ENV OJDK_VENDOR=${JDK_VENDOR:-"openjdk"} \
    OJDK_RELEASE=${JDK_RELEASE:-"jdk13"} \
    OJDK_VERS=${JDK_VERS:-"13.0.1"}  \
    OJDK_CYPHER=${JDK_CYP:-"cec27d702aa74d5a8630c65ae61e4305"} \
    OJDK_TYPE=${JDK_TYPE:-"jdk13"}

ENV OJDK_PKG="openjdk-${OJDK_VERS}_linux-x64_bin.tar.gz" 

ENV OJDK_HOST=${JDK_HOST:-"https://download.java.net/java/GA/$OJDK_RELEASE/${OJDK_CYPHER}/9/GPL"}
ENV OJDK_URL="${OJDK_HOST}/${OJDK_PKG}"

ENV LMS_JDK=${OJDK_RELEASE}

ENV BLDJDK_PKG="jdk-${OJDK_VERS}-ojdkbuild-linux-x64.zip"
ENV BLDJDK_HOST=${JDK_HOST:-"https://github.com/ojdkbuild/contrib_${OJDK_RELEASE}u-ci/releases/download/${OJDK_VERS}%2B9"}
               
# =========================================================================
#
#	JavaScript
#		NodeJS version 13
#
# =========================================================================

ENV NJS_VER=${NODEJS_VER:-"13"} \
    NJS_HOST=${NODEJS_HOST:-"https://deb.nodesource.com"} \
    NJS_DIR="NodeJS" 

ENV NJS_NAME="setup_${NJS_VER}.x" 
ENV NJS_URL=${NJS_HOST}/${NJS_NAME}  

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
#
#	PHP
#		PHP 5.6
#
# =========================================================================

ENV PHPVER=${PHP_VER:-"5.6"} \
    PHPCOMP=${PHP_COMP:-"1"}

# =========================================================================
#
#	QT
#		Qt4
#
# =========================================================================

ENV QTVER=${QT_VER:-"4"} 

# ========================================================================================
# ========================================================================================

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
    LMS_DESKTOP=${BUILD_DESKTOP:-"dcc"} \
    LMS_ICON=${BUILD_ICON:-"default.png"} \
    \
    LMS_FROM="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    LMS_PARENT="${FROM_PARENT}:${FROM_VERS}${FROM_EXT}${FROM_EXT_MOD}" \
    \
    LMS_NAME=${BUILD_NAME:-"ldc-langdev"} \
    LMS_VERSION=${BUILD_VERSION:-"dcc"} \
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
    LMSLIB_VERS_MOD=${LIB_VERS_MOD:-"-b3"}

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

RUN chmod +x /tmp/*.sh \
 \
 # =======================================================================================
 #
 #    install common ("global") dependecies
 #
 # =======================================================================================
 \
 && case ${DSTACK} in \
     "CPP")     /tmp/cc.sh      ;; \
     "GTK")     /tmp/gtk.sh     ;; \
     "QT")      /tmp/qt.sh      ;; \
     *)         echo "unknown stack"; exit 1 ;; \
    esac \
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
