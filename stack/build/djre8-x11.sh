#!/bin/bash
# ===========================================================================
#
#    ldc-stack:djre8-x11-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-djre8-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-djre8-x11-0.1.0-b4
docker rm ldc-stack-djre8-x11-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing ewsdocker/ldc-stack:djre8-x11 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack:djre8-x11-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack:djre8-x11-0.1.0-b4"
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
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-surface" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  \
  --network=pkgnet\
  --file Dockerfile \
  \
-t ewsdocker/ldc-stack:djre8-x11-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack:djre8-x11-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-stack-djre8-x11-0.1.0-b4 image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/djre8-x11.sh
