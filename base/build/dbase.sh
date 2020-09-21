#!/bin/bash
# ===========================================================================
#
#    ldc-base:dbase-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base:dbase container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dbase-0.1.0-b4 2>null
docker rm ldc-base-dbase-0.1.0-b4 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dbase image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dbase-0.1.0-b4 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dbase-0.1.0-b4 image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="BASE" \
  \
  --build-arg GOSU_REL="1.12" \
  --build-arg GOSU_VER="gosu-amd64" \
  --build-arg GOSU_HOST=http://alpine-nginx-pkgcache \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-base" \
  --build-arg BUILD_VERSION="dbase" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-core" \
  --build-arg FROM_VERS="dcore" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  --build-arg LIB_INSTALL="1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-base:dbase-0.1.0-b4  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dbase-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-base-dbase-0.1.0-b4 image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dbase.sh

