#!/bin/bash
cd ~/Development/ewslms/ldc-libre

# ****************************************************

declare -a cSoftVers="6.2.3"
declare -a cSoftName="libreoffice"

declare -a cDockerName="dlibre"

declare -a cNetwork="pkgnet"

declare -a cBuffer=""

# ****************************************************

declare    cRepo="ewsdocker"
declare    cName="ldc-libre"
declare    cVersion="office"
declare    cVersExt="6.2.3"
declare    cExtMod=""

declare    cRunApp="/bin/bash"

# ****************************************************

declare -a cNames=( "ldc-utilities"
                    "ldc-core" 
                    "ldc-lang"
                    "ldc-browser"
                    "ldc-eclipse"
                    "ldc-libre"
                    "ldc-desktop" 
                   )

declare -a cVersions=( "dcore" "dgui" )

declare -a cExts=()
declare -a cMods=( )

declare -a cBuffer=""

# ****************************************************
#
#   setRepo
#      Set the Repository name
#
#   Enter:
#      cRepo = repository name
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function setRepo()
{
	cRepo=${1:-"$cRepo"}
}

# ****************************************************
#
#   setName
#      Set the Image name
#
#   Enter:
#      cName = image name ( "repo"/"image":"version"-"vers_ext"-"ext_mods" )
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function setName()
{
	cName=${1:-"$cName"}
}

# ****************************************************
#
#   setVersion
#      Set the Version name
#
#   Enter:
#      cVersion = version name ( "repo"/"image":"version"-"vers_ext"-"ext_mods" )
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function setVersion()
{
	cVersion=${1:-"$cVersion"}
}

# ****************************************************
#
#   setVersExt
#      Set the Version extension
#
#   Enter:
#      cVersExt = version extension ( "repo"/"image":"version"-"vers_ext"-"ext_mods" )
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function setVersExt()
{
	cVersExt=${1:-"$cVersExt"}
}

# ****************************************************
#
#   setExtMod
#      Set the Version extension modifier
#
#   Enter:
#      cExtMod = version extension modifier ( "repo"/"image":"version"-"vers_ext"-"ext_mods" )
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function setExtMod()
{
	cExtMod=${1:-"$cExtMod"}
}

# ****************************************************
#
#   setRunApp
#      Set the application to run at start-up
#
#   Enter:
#      runApp = name of the application to start
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function setRunApp()
{
    cRunApp=${1:-"$cRunApp"}
}

# ****************************************************
#
#   imageName
#      Create the image name from the global vars
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function imageName()
{
    cImage=${cRepo}/${cName}:${cVersion}-${cVersExt}${cExtMod}
}

# ****************************************************
#
#   containerName
#      Create the container name from the global vars
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function containerName()
{
    cContainer="${cName}-${cVersion}-${cVersExt}${cExtMod}"
}

function addToBuffer()
{
    printf -v cBuffer "%s\n%s" "${cBuffer}" "${1}"
}

# ****************************************************
#
#   imageHeader
#      Create the docker command to build the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function imageHeader()
{
    cBuffer="docker build \"

    addToBuffer "--build-arg BUILD_BASE=/usr/local/ --build-arg RUN_APP=${cSoftName} \"
}

# ****************************************************
#
#   imageFooter
#      Create the docker command footer to build the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function imageFooter()
{
    addToBuffer "--network=${cNetwork} \"
    addToBuffer "--file Dockerfile.${cDockername} \"
    addToBuffer "-t ewsdocker/ldc-libre:office-${cVersExt} . "
}

# ****************************************************
#
#   imageBody
#      Create the docker command body to build the image
#
#   Enter:
#      none
#   Exit:
#      0 = success
#      non-zero = error number
#
# ****************************************************
function imageBody()
{
	[[ ${cLink2} -ne 0 ]] && addToBuffer "--build-arg LINK2=1 \"
	[[ ${cElink} -ne 0 ]] && addToBuffer "--build-arg ELINK=1 \"

    [[ ${cNetsurf} -ne 0 ]] && 
     {
     	addToBuffer "--build-arg NETSURF=1 --build-arg NETSURF_HOST=http://alpine-nginx-pkgcache \"
     }

    [[ ${cPalemoon} -ne 0 ]] && 
     {
     	addToBuffer "--build-arg PALEMOON=1 --build-arg PALEMOON_HOST=http://alpine-nginx-pkgcache \"
     }

    [[ ${cFirefox} -ne 0 ]] && 
     {
     	addToBuffer "--build-arg FIREFOX=1 --build-arg FIREFOX_HOST=http://alpine-nginx-pkgcache \"
     }

    [[ ${cWaterfox} -ne 0 ]] && 
     {
     	addToBuffer "--build-arg WATERFOX=1 --build-arg FIREFOX_HOST=http://alpine-nginx-pkgcache \"
     }

	addToBuffer "--build-arg LIB_HOST=http://alpine-nginx-pkgcache \"
    addToBuffer "--build-arg OFFICE_VER=${cVersExt} --build-arg OFFICE_HOST=http://alpine-nginx-pkgcache \"
}

docker create \
      -it \
      -e LRUN_APP="${cGenericName}" \
      \
      -v ${HOME}/bin:/userbin \
      -v ${HOME}/.local:/usrlocal \
      -v ${HOME}/.config/docker:/conf \
      -v ${HOME}/.config/docker/${cContainer}:/root \
      -v ${HOME}/.config/docker/${cContainer}/workspace:/workspace \
      \
      -e DISPLAY=unix${DISPLAY} \
      -v ${HOME}/.Xauthority:/root/.Xauthority \
      -v /tmp/.X11-unix:/tmp/.X11-unix \
      -v /dev/shm:/dev/shm \
      --device /dev/snd \
      \
      -v ${HOME}/Documents:/documents \
      -v ${HOME}/Source:/source \
      \
      --name=${cContainer} \
    ewsdocker/${cImage}
[[ $? -eq 0 ]] ||
 {
 	echo "create container ${cContainer} failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ${cContainer} successfully created."
echo "   ****"
echo "   **** run with "
echo "   ****    docker start ${cContainer}"
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

