#!/bin/bash

echo "   ********************************************"
echo "   ****"
echo "   **** stopping yad container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-yad-0.1.0-b4
docker rm ldc-dialog-yad-0.1.0-b4

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-yad-0.1.0-b4"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  --rm \
  -e LRUN_APP="/bin/bash" \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.local/ewsldc:/opt \
  \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-dialog-yad-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-dialog-yad-0.1.0/workspace:/workspace \
  \
  -v ${HOME}/Source:/source \
  -v ${HOME}/Pictures:/pictures \
  -v ${HOME}/Documents:/documents \
  -v ${HOME}/Development:/development \
  \
  --name=ldc-dialog-yad-0.1.0-b4 \
ewsdocker/ldc-dialog:yad-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-yad-0.1.0-b4 failed."
 	exit 2
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:yad-0.1.0-b4 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0
