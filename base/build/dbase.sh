#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-base:dbase${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base:dbase container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dbase${ldcvers}${ldcextv} 2>null
docker rm ldc-base-dbase${ldcvers}${ldcextv} 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dbase image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dbase${ldcvers}${ldcextv} 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dbase${ldcvers}${ldcextv} image "
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
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-core" \
  --build-arg FROM_VERS="dcore" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --build-arg LIB_VERSION="${libver}" \
  --build-arg LIB_VERS_MOD="${ldcextv}" \
  --build-arg LIB_INSTALL="1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-base:dbase${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dbase${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-base-dbase${ldcvers}${ldcextv} image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dbase.sh

