#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dialog container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-dialog${ldcvers}${ldcextv}
docker rm ldc-dialog-dialog${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-dialog${ldcvers}${ldcextv}"
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
      -v ${HOME}/bin:/userbin \
      -v ${HOME}/.local:/usrlocal \
      -v ${HOME}/.config/docker:/conf \
      -v ${HOME}/.config/docker/ldc-dialog-dialog${ldcvers}:/root \
      -v ${HOME}/.config/docker/ldc-dialog-dialog${ldcvers}/workspace:/workspace \
      \
      -v ${HOME}/Source:/source \
      -v ${HOME}/Documents:/documents \
      -v ${HOME}/Development:/development \
      \
      --name=ldc-dialog-dialog${ldcvers}${ldcextv} \
    ewsdocker/ldc-dialog:dialog${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-dialog${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:dialog${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

