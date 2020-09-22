#!/bin/bash
# ===========================================================================
#
#    ldc-base:dx11-surface-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-surface container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dx11-surface-0.1.0-b4
docker rm ldc-base-dx11-surface-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dx11-surface images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dx11-surface-0.1.0-b4

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dx11-surface-0.1.0-b4 container"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
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
  -v ${HOME}/.config/docker/ldc-base-dx11-surface-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-base-dx11-surface-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-base-dx11-surface-0.1.0-b4 \
ewsdocker/ldc-base:dx11-surface-0.1.0-b4 
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dx11-surface-0.1.0-b4 failed."
 	exit 2
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-surface-0.1.0-b4 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dx11-surface-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dx11-surface-0.1.0-b4 failed."
	exit 3
 }

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base-dx11-surface-0.1.0-b4 container"
echo "   ****"
echo "   ********************************************"
echo

docker rm ldc-base-dx11-surface-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dx11-surface-0.1.0-b4 failed."
	exit 4
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dx11-surface successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

