#!/bin/bash

# ===========================================================================
#
#    ldc-core:dcore-0.1.0-b3
#
# ===========================================================================
#cd ~/Development/ewsldc/ldc-framework/core

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcore container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-core-dcore-0.1.0-b3 2>null
docker rm ldc-core-dcore-0.1.0-b3 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing dcore image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-core:dcore-0.1.0-b3 

echo "   ********************************************"
echo "   ****"
echo "   **** getting new debian:10.4, if one is available"
echo "   ****"
echo "   ********************************************"
echo
docker pull debian:10.4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-core:dcore-0.1.0-b3 image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="CORE" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-core" \
  --build-arg BUILD_VERSION="dcore" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b3" \
  \
  --build-arg FROM_REPO="" \
  --build-arg FROM_PARENT="debian" \
  --build-arg FROM_VERS="10.4" \
  --build-arg FROM_EXT="" \
  --build-arg FROM_EXT_MOD="" \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-core:dcore-0.1.0-b3  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-core:dcore-0.1.0-b3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-core-dcore-0.1.0-b3 container"
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
  -v ${HOME}/.config/docker/ldc-core-dcore-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-core-dcore-0.1.0/workspace:/workspace \
  \
  --name=ldc-core-dcore-0.1.0-b3 \
ewsdocker/ldc-core:dcore-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-core-dcore-0.1.0-b3 failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-core-dcore-0.1.0-b3 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-core-dcore-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-core-dcore-0.1.0-b3 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-core:dcore successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

