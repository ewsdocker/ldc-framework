#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh


echo "   ********************************************"
echo "   ****"
echo "   **** stopping dkaptain container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-kaptain${ldcvers}${ldcextv}
docker rm ldc-dialog-kaptain${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-kaptain${ldcvers}${ldcextv}"
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
      -v ${HOME}/.config/docker:/conf \
      -v ${HOME}/.config/docker/ldc-dialog-kaptain${ldcvers}${ldcextv}:/root \
      -v ${HOME}/.config/docker/ldc-dialog-kaptain${ldcvers}${ldcextv}/workspace:/workspace \
      \
      -v ${HOME}/Source:/source \
      -v ${HOME}/Pictures:/pictures \
      -v ${HOME}/Documents:/documents \
      -v ${HOME}/Development:/development \
      \
      --name=ldc-dialog-kaptain${ldcvers}${ldcextv} \
    ewsdocker/ldc-dialog:kaptain${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-kaptain${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:kaptain${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

