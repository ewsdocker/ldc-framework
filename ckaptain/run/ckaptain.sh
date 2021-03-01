#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-ckaptain:ckaptain${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ckaptain-ckaptain${ldcvers}${ldcextv} container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ckaptain-ckaptain${ldcvers}${ldcextv}
docker rm ldc-ckaptain-ckaptain${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ckaptain-ckaptain${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
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
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain${ldcvers}${ldcextv}:/root \
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain${ldcvers}${ldcextv}/workspace:/workspace \
  \
  -v /etc/localtime:/etc/localtime:ro \
  \
  -v ${HOME}/Documents:/Documents \
  \
  --name=ldc-ckaptain-ckaptain${ldcvers}${ldcextv} \
ewsdocker/ldc-ckaptain:ckaptain${ldcvers}${ldcextv}
[[ $? -eq 0 ]] || exit $?
# {
# 	echo "create container ldc-ckaptain-ckaptain${ldcvers}${ldcextv} failed."
# 	exit 1
# }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-ckaptain:ckaptain${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0
