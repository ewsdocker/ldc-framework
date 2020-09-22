#!/bin/bash
# ===========================================================================
#
#    ldc-stack-dev:dcc-dev-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcc-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dev-dcc-dev-0.1.0-b4
docker rm ldc-stack-dev-dcc-dev-0.1.0-b4

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-dev-dcc-dev-0.1.0-b4"
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
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-stack-dev-dcc-dev-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-stack-dev-dcc-dev-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  --name=ldc-stack-dev-dcc-dev-0.1.0-b4 \
ewsdocker/ldc-stack-dev:dcc-dev-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-dev-dcc-dev-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dev-dcc-dev-0.1.0-b4 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-dev-dcc-dev-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-dev-dcc-dev-0.1.0-b4 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-stack-dev:dcc-dev successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

