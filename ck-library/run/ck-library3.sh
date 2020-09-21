#!/bin/bash

# ===========================================================================
#
#    ldc-ck-library:ck-library-0.1.0-b3
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ck-library:ck-library container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ck-library-ck-library-0.1.0-b3
docker rm ldc-ck-library-ck-library-0.1.0-b3

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ck-library-ck-library-0.1.0-b3"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  -e LRUN_APP="ck-archive" \
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
  -v ${HOME}/.config/docker/ldc-ck-library-ck-library-0.1.0-b3:/root \
  -v ${HOME}/.config/docker/ldc-ck-library-ck-library-0.1.0-b3/workspace:/workspace \
  \
  --mount source=pkgcache,target=/repo \
  \
  -v ${HOME}/Source:/source \
  \
  --name=ldc-ck-library-ck-library-0.1.0-b3 \
ewsdocker/ldc-ck-library:ck-library-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-ck-library-ck-library-0.1.0-b3 failed."
 	exit 2
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-ck-library:ck-library-0.1.0-b3 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

