#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

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

    echo ""
    echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
    echo ""
    echo "    Building \"${scriptName}\""
    echo ""
    echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
    echo ""

    build/${scriptName}.sh

    echo ""
    echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
    echo ""
    echo "    Successfully built \"${scriptName}\""
    echo ""
    echo "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++"
    echo ""

    return $?
}

#
#==================================================
#

cd ~/Development/ewsldc/ldc-framework/stack-apps

#buildScript "djre8-gtk2" ; [[ $? -eq 0 ]] || exit $?
buildScript "djdk8-gtk2" ; [[ $? -eq 0 ]] || exit $?
buildScript "djdk13-gtk3" ; [[ $? -eq 0 ]] || exit $?
buildScript "djdk13dev-gtk3" ; [[ $? -eq 0 ]] || exit $?
buildScript "dcpp-jdk13" ; [[ $? -eq 0 ]] || exit $?
buildScript "dfortran-jdk13" ; [[ $? -eq 0 ]] || exit $?
#buildScript "dfortran-gtk2" ; [[ $? -eq 0 ]] || exit $?
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

