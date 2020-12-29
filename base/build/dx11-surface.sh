#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-base:dx11-surface${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-surface container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dx11-surface${ldcvers}${ldcextv}
docker rm ldc-base-dx11-surface${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dx11-surface images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dx11-surface${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dx11-surface${ldcvers}${ldcextv}"
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
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-base" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --file Dockerfile \
  \
  -t ewsdocker/ldc-base:dx11-surface${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dx11-surface${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-base-dx11-surface${ldcvers}${ldcextv} image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dx11-surface.sh

