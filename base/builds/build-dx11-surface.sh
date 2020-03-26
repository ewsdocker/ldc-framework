#!/bin/bash
# ===========================================================================
#
#    ldc-base:dx11-surface-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-surface container"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-base-dx11-surface-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dx11-surface images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dx11-surface-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dx11-surface-0.1.0-b1"
echo "   ****"
echo "   ********************************************"
echo
docker build \
  --build-arg DNAME="SURFACE" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-base" \
  --build-arg BUILD_VERSION="dx11-surface" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-base" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  \
  --network=pkgnet \
  --file Dockerfile \
  \
  -t ewsdocker/ldc-base:dx11-surface-0.1.0-b1  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dx11-surface-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dx11-surface-0.1.0-b1 container"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  \
  -v /etc/localtime:/etc/localtime:ro \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-base-dx11-surface-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-base-dx11-surface-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-base-dx11-surface-0.1.0-b1 \
ewsdocker/ldc-base:dx11-surface-0.1.0-b1 
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dx11-surface-0.1.0-b1 failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-surface-0.1.0-b1 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dx11-surface-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dx11-surface-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base-dx11-surface-0.1.0-b1 container"
echo "   ****"
echo "   ********************************************"
echo

docker rm ldc-base-dx11-surface-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dx11-surface-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dx11-surface successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

