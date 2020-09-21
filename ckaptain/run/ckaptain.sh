#!/bin/bash

# ===========================================================================
#
#    ldc-ckaptain:ckaptain-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ckaptain-ckaptain-0.1.0-b4 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ckaptain-ckaptain-0.1.0-b4
docker rm ldc-ckaptain-ckaptain-0.1.0-b4

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ckaptain-ckaptain-0.1.0-b4"
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
  \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain-0.1.0-b4:/root \
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain-0.1.0-b4/workspace:/workspace \
  \
  -v /etc/localtime:/etc/localtime:ro \
  \
  -v ${HOME}/Documents:/Documents \
  \
  --name=ldc-ckaptain-ckaptain-0.1.0-b4 \
ewsdocker/ldc-ckaptain:ckaptain-0.1.0-b4
[[ $? -eq 0 ]] || exit $?
# {
# 	echo "create container ldc-ckaptain-ckaptain-0.1.0-b4 failed."
# 	exit 1
# }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-ckaptain:ckaptain-0.1.0-b4 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0
