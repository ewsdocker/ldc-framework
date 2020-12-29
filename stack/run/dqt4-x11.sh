#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack:dqt4-x11${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dqt4-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dqt4-x11${ldcvers}${ldcextv}
docker rm ldc-stack-dqt4-x11${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-dqt4-x11${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
   -d \
   --rm \
   \
   -v /etc/localtime:/etc/localtime:ro \
   \
   -e LMS_BASE="/root/.local" \
   -e LMS_HOME="/root" \
   -e LMS_CONF="/root/.config" \
   \
   -v ${HOME}/bin:/userbin \
   -v ${HOME}/.local:/usrlocal \
   -v ${HOME}/.config/docker:/conf \
   -v ${HOME}/.config/docker/ldc-stack-dqt4-x11${ldcvers}:/root \
   -v ${HOME}/.config/docker/ldc-stack-dqt4-x11${ldcvers}/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-stack-dqt4-x11${ldcvers}${ldcextv} \
 ewsdocker/ldc-stack:dqt4-x11${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-dqt4-x11${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dqt4-x11${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-dqt4-x11${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-dqt4-x11${ldcvers}${ldcextv} failed."
	exit 3
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-stack:dqt4-x11${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

