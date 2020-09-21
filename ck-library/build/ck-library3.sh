#!/bin/bash

# ===========================================================================
#
#    ldc-ck-library:ck-library-0.1.0-b3
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/ck-library

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ck-library:ck-library container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ck-library-ck-library-0.1.0-b3
docker rm ldc-ck-library-ck-library-0.1.0-b3

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-ck-library-ck-library image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-ck-library:ck-library-0.1.0-b3

echo "   ***************************************************"
echo "   ****"
echo "   **** building ldc-ck-library:ck-library-0.1.0-b3"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CK-LIB" \
  --build-arg QT_VER="QT4" \
  \
  --build-arg RUN_APP="ck-archive" \
  \
  --build-arg KAPTAIN_HOST="http://alpine-nginx-pkgcache" \
  --build-arg KAPTAIN_VERS="0.8" \
  \
  --build-arg BUILD_PKG="Kaptain v. 0.8" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_DESKTOP="CK-Lib" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-ck-library" \
  --build-arg BUILD_VERSION="ck-library" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b3" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dqt4-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b3" \
  \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b3" \
  \
  --build-arg CKLIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg CKLIB_DIR="/ckaptain-lib" \
  --build-arg CKLIB_VERS="0.1.0" \
  --build-arg CKLIB_VERSX="-b3" \
  --build-arg CKLIB_RELEASE="v.0" \
  --build-arg CKLIB_NAME="ckaptain-lib" \
  --build-arg CKLIB_DEST="/repo" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-ck-library:ck-library-0.1.0-b3  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-ck-library:ck-library-0.1.0-b3 failed."
 	exit 1
 }

echo "   ***************************************************************"
echo "   ****"
echo "   **** ldc-ck-library-ck-library-0.1.0-b3 successfully installed"
echo "   ****"
echo "   ***************************************************************"
echo

. run/ck-library3.sh

