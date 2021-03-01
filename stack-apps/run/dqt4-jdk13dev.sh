#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:dqt4-jdk13dev
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dqt4-jdk13dev container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}
docker rm ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}"
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
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.local/ewsldc:/opt \
  \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}:/root \
  -v ${HOME}/.config/docker/ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv} \
ewsdocker/ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv} failed."
	exit 3
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

