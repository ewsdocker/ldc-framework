#!/bin/bash
# ===========================================================================
#
#    ldc-stack-dev:dcc-dev-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcc-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dev-dcc-dev-0.1.0-b4
docker rm ldc-stack-dev-dcc-dev-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing dcc-x11 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-dev:dcc-dev-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-dev:dcc-dev-0.1.0-b4"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CPP" \
  \
  --build-arg CC_VER="6" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-dev" \
  --build-arg BUILD_VERSION="dcc-dev" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-dev" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-stack-dev:dcc-dev-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-dev:dcc-dev-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-dev-dcc-dev-0.1.0-b4"
echo "   ****"
echo "   ***********************************************"
echo

. run/dcc-dev.sh
