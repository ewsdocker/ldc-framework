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

cd ~/Development/ewsldc/ldc-applications/browser

cd ../console

buildScript "nano" ; [[ $? -eq 0 ]] || exit $?
buildScript "tumblr"

#

cd ../desktop

buildScript "dia" ; [[ $? -eq 0 ]] || exit $?
buildScript "gimp" ; [[ $? -eq 0 ]] || exit $?
buildScript "mousepad" ; [[ $? -eq 0 ]] || exit $?
buildScript "obs-studio" 
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

