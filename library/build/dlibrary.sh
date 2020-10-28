#!/bin/bash

# ===========================================================================
#
#    ldc-library:dlibrary-0.1.6-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/library

echo "   ********************************************"
echo "   ****"
echo "   **** removing dlibrary images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-library:dlibrary-0.1.6-b4

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-library:dlibrary-0.1.6-b4 image"
echo "   ****"
echo "   ********************************************"
echo

docker build \
  --build-arg RUN_APP="lms-utilities-install" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-library" \
  --build-arg BUILD_VERSION="dlibrary" \
  --build-arg BUILD_VERS_EXT="-0.1.6" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-core" \
  --build-arg FROM_VERS="dcore" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg ULMSLIB_NAME="ldc-library" \
  --build-arg ULMSLIB_USR_DIR="usr" \
  --build-arg ULMSLIB_ETC_DIR="etc/lms" \
  --build-arg ULMSLIB_DEST="/repo" \
  --build-arg ULMSLIB_VERS="0.1.6" \
  --build-arg ULMSLIB_VERSX="-b4" \
  \
  --file Dockerfile \
-t ewsdocker/ldc-library:dlibrary-0.1.6-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-library:dlibrary-0.1.6-b4 failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-library-dlibrary-0.1.6-b4 built."
echo "   ****"
echo "   ********************************************"
echo

. run/dlibrary.sh

