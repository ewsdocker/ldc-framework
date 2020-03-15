#!/bin/bash
# ===========================================================================
#
#    ldc-stack:djre8-x11-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/ldc-stack

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-djre8-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-stack-djre8-x11-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing ewsdocker/ldc-stack:djre8-x11 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack:djre8-x11-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack:djre8-x11-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="JDK" \
  --build-arg JDK_TYPE="jre8" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack" \
  --build-arg BUILD_VERSION="djre8-x11" \
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
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  \
  --network=pkgnet\
  --file Dockerfile.dstack \
  \
-t ewsdocker/ldc-stack:djre8-x11-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack:djre8-x11-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-djre8-x11-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
   -d \
   --rm \
   \
  -v /etc/localtime:/etc/localtime:ro \
  \
   -e LMS_BASE="/root/.local" \
   -e LMS_HOME="/root" \
   -e LMS_CONF="/root/.config" \
   \
   -v ${HOME}/bin:/userbin \
   -v ${HOME}/.local:/usrlocal \
   -v ${HOME}/.config/docker:/conf \
   -v ${HOME}/.config/docker/ldc-stack-djre8-x11-0.1.0:/root \
   -v ${HOME}/.config/docker/ldc-stack-djre8-x11-0.1.0/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-stack-djre8-x11-0.1.0-b1 \
 ewsdocker/ldc-stack:djre8-x11-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-djre8-x11-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-djre8-x11-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-djre8-x11-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-djre8-x11-0.1.0-b1 failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-stack:djre8-x11-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0
