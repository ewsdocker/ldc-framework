#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:djre8-gtk2${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps:djre8-gtk2 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv}
docker rm ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
   -d \
   --rm \
   \
  -v /etc/localtime:/etc/localtime:ro \
  \
   -e LMS_BASE="${HOME}/.local" \
   -e LMS_HOME="${HOME}" \
   -e LMS_CONF="${HOME}/.config" \
   \
   -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.local/ewsldc:/opt \
  \
   -v ${HOME}/.config/docker:/conf \
   -v ${HOME}/.config/docker/ldc-stack-apps-djre8-gtk2${ldcvers}:/root \
   -v ${HOME}/.config/docker/ldc-stack-apps-djre8-gtk2${ldcvers}/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv} \
 ewsdocker/ldc-stack-apps:djre8-gtk2${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-apps-djre8-gtk2${ldcvers}${ldcextv} failed."
	exit 3
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-stack-apps:djre8-gtk2${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

