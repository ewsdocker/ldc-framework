#!/bin/bash
# ===========================================================================
#
#    ldc-stack-apps:dphp5.6-js14-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dphp5.6-js14 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-dphp5.6-js14-0.1.0-b4
docker rm ldc-stack-apps-dphp5.6-js14-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing dphp5.6-js14 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:dphp5.6-js14-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:dphp5.6-js14-0.1.0-b4"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="PHP" \
  --build-arg PHP_VER="5.6" \
  --build-arg PHP_COMP="1" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="dphp5.6-js14" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-apps" \
  --build-arg FROM_VERS="djs14-jdk13" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-stack-apps:dphp5.6-js14-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:dphp5.6-js14-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-apps-dphp5.6-js14-0.1.0-b4"
echo "   ****"
echo "   ***********************************************"
echo

. run/dphp5.6-js14.sh
