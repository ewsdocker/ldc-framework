#!/bin/bash
# ===========================================================================
#
#    ldc-stack-dev:dqt4-dev-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dev-dqt4-dev container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dev-dqt4-dev-0.1.0-b4
docker rm ldc-stack-dev-dqt4-dev-0.1.0-b4

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-dev-dqt4-dev-0.1.0-b4"
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
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-stack-dev-dqt4-dev-0.1.0-b4:/root \
  -v ${HOME}/.config/docker/ldc-stack-dev-dqt4-dev-0.1.0-b4/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-stack-dev-dqt4-dev-0.1.0-b4 \
ewsdocker/ldc-stack-dev:dqt4-dev-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-dev-dqt4-dev-0.1.0-b4 failed."
 	exit 2
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dev-dqt4-dev-0.1.0-b4 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-dev-dqt4-dev-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-dev-dqt4-dev-0.1.0-b4 failed."
	exit 3
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-stack-dev:dqt4-dev-0.1.0-b4 successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

