#!/bin/bash

declare instList=""

# =========================================================================
#
#	installList
#		the instList has been build, now execute it
#
#   Enter:
#		none
#
# =========================================================================
function installList()
{
	echo ""
	echo "$instList"
	echo ""

	$instList
	[[ $? -eq 0 ]] || exit $?

    return 0
}

# =========================================================================
#
#	addPkg
#		add specified package to install list
#
#   Enter:
#		instPkg = "package" name to add to the installation list
#		instComment = comment... not used, but tolerated for documentation
#
# =========================================================================
function addPkg()
{
	local instPkg="${1}"
	local instComment="${2}"

    printf -v instList "%s %s" "${instList}" "${instPkg}"
    return 0
}

# =========================================================================
#
#	installPackage
#		download & install requested package
#
#   Enter:
#		instPkg = "package" name downloaded - what you are going to extract from
#		instUrl = server to download the pkg from
#
# =========================================================================
function installPackage()
{
	local instPkg="${1}"
	local instUrl="${2}"

    wget "${instUrl}"
    [[ $? -eq 0 ]] || return $?

    dpkg -i "${instPkg}"
    [[ $? -eq 0 ]] || return $?

    rm "${instPkg}"

    return 0
}

# =========================================================================

apt-get -y update

addPkg "apt-get install -y"

addPkg "libnotify4"           "sends desktop notifications to notify daemon."
addPkg "libwebkit2gtk-4.0.37" "Web content engine library for GTK+"

addPkg "zenity"               "display GTK+ dialogs from shell scripts"
addPkg "zenity-common"        "zenity common files"

installList
[[ $? -eq 0 ]] || exit $?

apt-get clean all

exit 0
