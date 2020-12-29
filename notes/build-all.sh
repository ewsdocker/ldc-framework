#!/bin/bash

declare ldcvers="-0.1.0"
declare ldcextv="-b4"

declare ldccont="${ldcvers}"
declare ldclib="-0.1.6"

# =====================================================================
#
#    buildScript
#        Build the docker image and then the docker container
#
#    Enter:
#        scriptName = the name of the script
#    Exit:
#        0 = no error
#        non-zero = error code
#
# =====================================================================
function buildScript()
{
    local scriptName=${1:-""}

    [[ -z "${scriptName}" ]] && return 1
    build/${scriptName}.sh

    return $?
}

#
#==================================================
#

cd ~/Development/ewsldc/ldc-framework/core

#
#==================================================
#

cd ../core

buildScript "dcore" ; [[ $? -eq 0 ]] || exit $?

#
#==================================================
#

cd ../library

buildScript "dlibrary" ; [[ $? -eq 0 ]] || exit $?

#
#==================================================
#

cd ../base

buildScript "dbase" ; [[ $? -eq 0 ]] || exit $?
buildScript "dperl" ; [[ $? -eq 0 ]] || exit $?
buildScript "dx11-base" ; [[ $? -eq 0 ]] || exit $?
buildScript "dx11-surface" ; [[ $? -eq 0 ]] || exit $?
buildScript "dx11-dev" ; [[ $? -eq 0 ]] || exit $?

#
cd ../stack

buildScript "dcc" ; [[ $? -eq 0 ]] || exit $?
buildScript "dcc-x11" ; [[ $? -eq 0 ]] || exit $?
buildScript "dgtk2" ; [[ $? -eq 0 ]] || exit $?
buildScript "dgtk3" ; [[ $? -eq 0 ]] || exit $?
buildScript "dgtk2-x11" ; [[ $? -eq 0 ]] || exit $?
buildScript "dgtk3-x11" ; [[ $? -eq 0 ]] || exit $?
buildScript "dqt4" ; [[ $? -eq 0 ]] || exit $?
buildScript "dqt4-x11" ; [[ $? -eq 0 ]] || exit $?

#
cd ../stack-dev

buildScript "dcc-dev" ; [[ $? -eq 0 ]] || exit $?
buildScript "dgtk3-dev" ; [[ $? -eq 0 ]] || exit $?
buildScript "dqt4-dev" ; [[ $? -eq 0 ]] || exit $?

#
cd ../stack-apps

buildScript "djre8-gtk2" ; [[ $? -eq 0 ]] || exit $?
buildScript "djdk8-gtk2" ; [[ $? -eq 0 ]] || exit $?
buildScript "djdk13-gtk3" ; [[ $? -eq 0 ]] || exit $?
buildScript "djdk13dev-gtk3" ; [[ $? -eq 0 ]] || exit $?
buildScript "dcpp-jdk13" ; [[ $? -eq 0 ]] || exit $?
buildScript "dfortran-jdk13" ; [[ $? -eq 0 ]] || exit $?
buildScript "dfortran-gtk2" ; [[ $? -eq 0 ]] || exit $?
buildScript "djs13-jdk13" ; [[ $? -eq 0 ]] || exit $?
buildScript "djs14-jdk13" ; [[ $? -eq 0 ]] || exit $?
buildScript "dphp5.6-jdk13" ; [[ $? -eq 0 ]] || exit $?
buildScript "dphp5.6-js14" ; [[ $? -eq 0 ]] || exit $?
buildScript "dqt4-jdk13dev" ; [[ $? -eq 0 ]] || exit $?

#
cd ../dialog

buildScript "dialog" ; [[ $? -eq 0 ]] || exit $?
buildScript "kaptain" ; [[ $? -eq 0 ]] || exit $?
buildScript "whiptail" ; [[ $? -eq 0 ]] || exit $?
buildScript "zenity" ; [[ $? -eq 0 ]] || exit $?

#
cd ../ck-library

buildScript "ck-library" ; [[ $? -eq 0 ]] || exit $?

#
cd ../ckaptain

buildScript "ckaptain" ; [[ $? -eq 0 ]] || exit $?

# ===============================================
#
# ldc-applications
#
# ===============================================

cd ~/Development/ewsldc/ldc-applications/browser

cd ../browser

buildScript "ffquantum" ; [[ $? -eq 0 ]] || exit $?
buildScript "firefox"  ; [[ $? -eq 0 ]] || exit $?
buildScript "firefox-esr" ; [[ $? -eq 0 ]] || exit $?
buildScript "netsurf" ; [[ $? -eq 0 ]] || exit $?
buildScript "palemoon" ; [[ $? -eq 0 ]] || exit $?
buildScript "waterfox-classic" ; [[ $? -eq 0 ]] || exit $?
buildScript "waterfox-current" ; [[ $? -eq 0 ]] || exit $?

#

cd ../console

buildScript "nano" ; [[ $? -eq 0 ]] || exit $?
buildScript "tumblr"

#

cd ../desktop

buildScript "dia" ; [[ $? -eq 0 ]] || exit $?
buildScript "gimp" ; [[ $? -eq 0 ]] || exit $?
buildScript "mousepad" ; [[ $? -eq 0 ]] || exit $?
buildScript "obs-studio"  ; [[ $? -eq 0 ]] || exit $?
buildScript "ripme" ; [[ $? -eq 0 ]] || exit $?
buildScript "shotcut" ; [[ $? -eq 0 ]] || exit $?

#

cd ../eclipse

buildScript "cpp" ; [[ $? -eq 0 ]] || exit $?
buildScript "fortran" ; [[ $? -eq 0 ]] || exit $?
buildScript "java" ; [[ $? -eq 0 ]] || exit $?
buildScript "javascript" ; [[ $? -eq 0 ]] || exit $?
buildScript "php" ; [[ $? -eq 0 ]] || exit $?
buildScript "qt" ; [[ $? -eq 0 ]] || exit $?
buildScript "bash" ; [[ $? -eq 0 ]] || exit $?

#

cd ../games

buildScript "mahjongg" ; [[ $? -eq 0 ]] || exit $?
buildScript "sol" ; [[ $? -eq 0 ]] || exit $?

#

cd ../libre

buildScript "office" ; [[ $? -eq 0 ]] || exit $?
buildScript "office-jdk" ; [[ $? -eq 0 ]] || exit $?

# ===============================================
#
# ldc-alpine
#
# ===============================================

cd ~/Development/ewsldc/ldc-alpine/console

#
cd ../console

buildScript "htop" ; [[ $? -eq 0 ]] || exit $?

#
cd ../foundation

buildScript "core" ; [[ $? -eq 0 ]] || exit $?
buildScript "base" ; [[ $? -eq 0 ]] || exit $?

#
cd ../server

buildScript "ftp" ; [[ $? -eq 0 ]] || exit $?
buildScript "httpd" ; [[ $? -eq 0 ]] || exit $?
buildScript "nginx" ; [[ $? -eq 0 ]] || exit $?
buildScript "nginx-dev" ; [[ $? -eq 0 ]] || exit $?
buildScript "nginx-pkgcache" ; [[ $? -eq 0 ]] || exit $?

#
cd ../server-client

buildScript "nginx" ; [[ $? -eq 0 ]] || exit $?
buildScript "pkgcache" ; [[ $? -eq 0 ]] || exit $?

