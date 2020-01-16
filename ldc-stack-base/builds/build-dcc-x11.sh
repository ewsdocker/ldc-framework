#!/bin/bash
# ===========================================================================
#
#    ldc-stack-base:dcc-x11-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-stack-base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcc-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-stack-base-dcc-x11-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dcc-x11 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-base:dcc-x11-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-base:dcc-x11-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CPP" \
  \
  --build-arg CC_VER="6" \
  --build-arg CC_TYPE="WAYLAND" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-base" \
  --build-arg BUILD_VERSION="dcc-x11" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-surface" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --network=pkgnet \
  \
  --file Dockerfile.dstack-base \
  -t ewsdocker/ldc-stack-base:dcc-x11-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-base:dcc-x11-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-base-dcc-x11-0.1.0-b1"
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
  -v ${HOME}/.config/docker/ldc-stack-base-dcc-x11-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-stack-base-dcc-x11-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  --name=ldc-stack-base-dcc-x11-0.1.0-b1 \
ewsdocker/ldc-stack-base:dcc-x11-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-base-dcc-x11-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-base-dcc-x11-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-base-dcc-x11-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-base-dcc-x11-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-stack-base:dcc-x11 successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

