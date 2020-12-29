#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-base:dx11-dev${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dx11-dev container"
echo "   ****"
echo "   ********************************************"
echo
docker stop "ldc-base-dx11-dev${ldcvers}${ldcextv}"
docker rm "ldc-base-dx11-dev${ldcvers}${ldcextv}"

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dx11-dev images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dx11-dev${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dx11-dev${ldcvers}${ldcextv}"
echo "   ****"
echo "   ********************************************"
echo
docker build \
  --build-arg DNAME="DEV" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-base" \
  --build-arg BUILD_VERSION="dx11-dev" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-surface" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --file Dockerfile \
  \
  -t ewsdocker/ldc-base:dx11-dev${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dx11-dev${ldcvers}${ldcextv} failed."
 	exit 1
 }

. run/dx11-dev.sh

