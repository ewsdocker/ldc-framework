#!/bin/bash
# ===========================================================================
#
#    ldc-langdev:dcc-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-langdev

echo "   ********************************************"
echo "   ****"
echo "   **** stopping gpp6 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-langdev-dcc-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dcc image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-langdev:dcc-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-langdev:dcc-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="CPP" \
  \
  --build-arg CC_VER="6" \
  --build-arg CC_GUI="0" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-langdev" \
  --build-arg BUILD_VERSION="dcc" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_PARENT="ldc-lang" \
  --build-arg FROM_VERS="dcc" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile.dlangdev \
-t ewsdocker/ldc-langdev:dcc-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-langdev:dcc-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-langdev-dcc-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  --rm \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-langdev-dcc-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-langdev-dcc-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-langdev-dcc-0.1.0-b1 \
ewsdocker/ldc-langdev:dcc-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-langdev-dcc-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-langdev-dcc-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-langdev-dcc-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-langdev-dcc-0.1.0-b1 failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-langdev:dcc-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

