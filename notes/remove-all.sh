#!/bin/bash

declare ldcvers="-0.1.0"
declare ldcextv="-b4"

declare ldccont="${ldcvers}"
declare ldclib="-0.1.6"

# =====================================================================
#
#    removeContainer
#        Stop and Remove the container
#
#    Enter:
#        limageName = the name of the image base (core, base, etc.)
#        lcontainerName = name of the container
#        lcontainerType = 0 - standard, non-zero = shortened name
#    Exit:
#        none
#
# =====================================================================
function removeContainer()
{
    local limageName="${1}"
    local lcontainerName="${2}"
    local lcontainerType=${3:-"0"}

    echo "*****************************************************************************"
    echo

    if [[ "${lcontainerType}" -eq "0" ]]
    then
        echo "Removing container: ldc-${limageName}-${lcontainerName}${ldcvers}${ldcextv}"

        docker stop "ldc-${limageName}-${lcontainerName}${ldcvers}${ldcextv}" >/dev/null 2>/dev/null
        docker rm "ldc-${limageName}-${lcontainerName}${ldcvers}${ldcextv}"  >/dev/null 2>/dev/null
    else
        echo "Removing container: ldc-${lcontainerName}"

        docker stop "ldc-${lcontainerName}"  >/dev/null 2>/dev/null
        docker rm   "ldc-${lcontainerName}"  >/dev/null 2>/dev/null
    fi

    echo
    echo "*****************************************************************************"

}

# =====================================================================
#
#    removeImage
#        Remove the ewsdocker image
#
#    Enter:
#        limageName = the name of the image base (core, base, etc.)
#        lcontainerName = name of the container
#    Exit:
#        none
#
# =====================================================================
function removeImage()
{
    local limageName="${1}"
    local lcontainerName="${2}"

    removeContainer "${limageName}" "${lcontainerName}" "0"

    echo "*****************************************************************************"
    echo
    echo "Removing image: ewsdocker/ldc-${limageName}:${lcontainerName}${ldcvers}${ldcextv}"
    echo
    echo "*****************************************************************************"

    docker rmi "ewsdocker/ldc-${limageName}:${lcontainerName}${ldcvers}${ldcextv}" >/dev/null 2>/dev/null
}

# =====================================================================
#
#    Start of program script
#
# =====================================================================

ldcvers="${ldccont}"

echo "*****************************************************************************"
echo
echo "Removing all docker ldc images and containers"
echo
echo "*****************************************************************************"

removeContainer "browser" "ffquantum" "1"
removeContainer "browser" "firefox-esr" "1"
removeContainer "browser" "firefox" "1"

removeImage "browser" "waterfox-current"
removeImage "browser" "waterfox"
removeImage "browser" "palemoon"
removeImage "browser" "netsurf"
removeImage "browser" "firefox"
removeImage "browser" "firefox-esr"
removeImage "browser" "ffquantum"

removeImage "console" "tumblr"
removeImage "console" "nano"

removeImage "desktop" "shotcut"
removeImage "desktop" "ripme"
removeImage "desktop" "obs-studio"
removeImage "desktop" "mousepad"
removeImage "desktop" "gimp"
removeImage "desktop" "dia"
#
removeImage "eclipse" "rust"
removeImage "eclipse" "qt"
removeImage "eclipse" "php"
removeImage "eclipse" "javascript"
removeImage "eclipse" "java"
removeImage "eclipse" "fortran"
removeImage "eclipse" "cpp"
removeImage "eclipse" "bash"
#
#
removeImage "games" "sol"
removeImage "games" "mahjongg"
#
#
removeImage "libre" "office-jdk"
removeImage "libre" "office"
#
#
# ==============================================================
#
#
removeImage "ckaptain" "ckaptain"
#
#
removeImage "ck-library" "ck-library"
#
#
removeImage "dialog" "zenity"
removeImage "dialog" "yad"
removeImage "dialog" "whiptail"
removeImage "dialog" "kaptain"
removeImage "dialog" "dialog"
#
#
removeImage "stack-apps" "dqt4-jdk13dev"
removeImage "stack-apps" "dphp5.6-jdk13"
removeImage "stack-apps" "dphp5.6-js14"
removeImage "stack-apps" "djs13-jdk13"
removeImage "stack-apps" "djs14-jdk13"
removeImage "stack-apps" "djre8-gtk2"
removeImage "stack-apps" "djdk13dev-gtk3"
removeImage "stack-apps" "djdk13-gtk3"
removeImage "stack-apps" "djdk8-gtk2"
removeImage "stack-apps" "dfortran-jdk13"
removeImage "stack-apps" "dfortran-gtk2"
removeImage "stack-apps" "dcpp-jdk13"
#
#
removeImage "stack-dev" "dcc-dev"
removeImage "stack-dev" "dgtk3-dev"
removeImage "stack-dev" "dqt4-dev"
#
#
removeImage "stack" "dqt4"
removeImage "stack" "dqt4-x11"
removeImage "stack" "djre8-x11"
removeImage "stack" "dgtk3-x11"
removeImage "stack" "dgtk3"
removeImage "stack" "dgtk2-x11"
removeImage "stack" "dgtk2"
removeImage "stack" "dcc-x11"
removeImage "stack" "dcc"
#
#
removeImage "base" "dx11-dev"
removeImage "base" "dx11-surface"
removeImage "base" "dx11-base"
removeImage "base" "dperl"
removeImage "base" "dbase"
#
#
ldcvers="${ldclib}"

removeImage "library" "dlibrary"

ldcvers="${ldccont}"

#
#
removeImage "core" "dcore"

echo "*****************************************************************************"
echo
echo "Removal complete"
echo
echo "*****************************************************************************"

