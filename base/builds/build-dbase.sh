#!/bin/bash
# ===========================================================================
#
#    ldc-base:dbase-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base:dbase container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dbase-0.1.0-b1 2>null
docker rm ldc-base-dbase-0.1.0-b1 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dbase image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dbase-0.1.0-b1 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dbase-0.1.0-b1 image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="BASE" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-base" \
  --build-arg BUILD_VERSION="dbase" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-core" \
  --build-arg FROM_VERS="dcore" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --build-arg GOSU_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-base:dbase-0.1.0-b1  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dbase-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dbase-0.1.0-b1 container"
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
  -v ${HOME}/.config/docker/ldc-base-dbase-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-base-dbase-0.1.0/workspace:/workspace \
  \
  --name=ldc-base-dbase-0.1.0-b1 \
ewsdocker/ldc-base:dbase-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-base-dbase-0.1.0-b1 failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dbase-0.1.0-b1 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dbase-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dbase-0.1.0-b1 failed."
 }

docker rm ldc-base-dbase-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dbase-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dbase successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

