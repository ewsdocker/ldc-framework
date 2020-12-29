#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack:dqt4-x11${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dqt4-x11 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dqt4-x11${ldcvers}${ldcextv}
docker rm ldc-stack-dqt4-x11${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ewsdocker/ldc-stack:dqt4-x11 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack:dqt4-x11${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack:dqt4-x11${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="QT" \
  --build-arg QT_VER="QT4" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack" \
  --build-arg BUILD_VERSION="dqt4-x11" \
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
-t ewsdocker/ldc-stack:dqt4-x11${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack:dqt4-x11${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-stack-dqt4-x11${ldcvers}${ldcextv} image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dqt4-x11.sh
