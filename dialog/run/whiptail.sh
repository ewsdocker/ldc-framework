#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh


echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-dialog-whiptail container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-whiptail${ldcvers}${ldcextv}
docker rm ldc-dialog-whiptail${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-whiptail${ldcvers}${ldcextv} "
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  --rm \
  -v /etc/localtime:/etc/localtime:ro \
  \
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
  -v ${HOME}/.config/docker/ldc-dialog-whiptail${ldcvers}:/root \
  -v ${HOME}/.config/docker/ldc-dialog-whiptail${ldcvers}/workspace:/workspace \
  \
  -v ${HOME}/Source:/source \
  -v ${HOME}/Documents:/documents \
  -v ${HOME}/Development:/development \
  \
  --name=ldc-dialog-whiptail${ldcvers}${ldcextv} \
ewsdocker/ldc-dialog:whiptail${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-whiptail${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:whiptail${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

