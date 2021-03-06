#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-dev:dgtk3-dev${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-dev

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dev-dgtk3-dev container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dev-dgtk3-dev${ldcvers}${ldcextv}
docker rm ldc-stack-dev-dgtk3-dev${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ewsdocker/ldc-stack-dev:dgtk3-dev image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-dev:dgtk3-dev${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-dev:dgtk3-dev${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="GTK" \
  --build-arg GTK_VER="GTK3" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-dev" \
  --build-arg BUILD_VERSION="dgtk3-dev" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-dev" \
  --build-arg FROM_VERS="dcc-dev" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --file Dockerfile \
  \
-t ewsdocker/ldc-stack-dev:dgtk3-dev${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-dev:dgtk3-dev${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-dev-dgtk3-dev${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

. run/dgtk3-dev.sh
