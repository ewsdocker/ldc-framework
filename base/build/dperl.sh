#!/bin/bash
# ===========================================================================
#
#    ldc-base:dperl-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dperl-0.1.0-b4 2>null
docker rm ldc-base-dperl-0.1.0-b4 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dperl image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dperl-0.1.0-b4 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dperl-0.1.0-b4 image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="PERL" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-base" \
  --build-arg BUILD_VERSION="dperl" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --build-arg GOSU_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-base:dperl-0.1.0-b4  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dperl-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-base-dperl-0.1.0-b4 image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dperl.sh

