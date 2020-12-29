#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-base:dperl${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dperl${ldcvers}${ldcextv} 2>null
docker rm ldc-base-dperl${ldcvers}${ldcextv} 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dperl image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dperl${ldcvers}${ldcextv} 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dperl${ldcvers}${ldcextv} image "
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
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --build-arg GOSU_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-base:dperl${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dperl${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-base-dperl${ldcvers}${ldcextv} image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dperl.sh

