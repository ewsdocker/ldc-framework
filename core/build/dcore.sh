#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-core:dcore
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/core

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcore container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-core-dcore${ldcvers}${ldcextv} 2>null
docker rm ldc-core-dcore${ldcvers}${ldcextv} 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing dcore image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-core:dcore${ldcvers}${ldcextv} 

echo "   ********************************************"
echo "   ****"
echo "   **** getting new debian:${ldcdebian}, if one is available"
echo "   ****"
echo "   ********************************************"
echo
docker pull debian:"${ldcdebian}"

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-core:dcore${ldcvers}${ldcextv} image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="CORE" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-core" \
  --build-arg BUILD_VERSION="dcore" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="" \
  --build-arg FROM_PARENT="debian" \
  --build-arg FROM_VERS="${ldcdebian}" \
  --build-arg FROM_EXT="" \
  --build-arg FROM_EXT_MOD="" \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-core:dcore${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-core:dcore${ldcvers}${ldcextv} failed."
 	exit 1
 }

. run/dcore.sh
