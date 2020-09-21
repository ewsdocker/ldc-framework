#!/bin/bash
# ===========================================================================
#
#    ldc-base:dx11-surface-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-surface container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dx11-surface-0.1.0-b4
docker rm ldc-base-dx11-surface-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dx11-surface images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dx11-surface-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dx11-surface-0.1.0-b4"
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
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-base" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  \
  --network=pkgnet \
  --file Dockerfile \
  \
  -t ewsdocker/ldc-base:dx11-surface-0.1.0-b4  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dx11-surface-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-base-dx11-surface-0.1.0-b4 image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dx11-surface.sh

