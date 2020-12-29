#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack:dgtk3-x11${ldcvers}${ldcextv} 
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dgtk3-x11${ldcvers}${ldcextv} container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-stack-dgtk3-x11${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ewsdocker/ldc-stack:dgtk3-x11${ldcvers}${ldcextv} image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack:dgtk3-x11${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack:dgtk3-x11${ldcvers}${ldcextv}"
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
  --build-arg BUILD_NAME="ldc-stack" \
  --build-arg BUILD_VERSION="dgtk3-x11" \
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
-t ewsdocker/ldc-stack:dgtk3-x11${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack:dgtk3-x11${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-stack-dgtk3-x11${ldcvers}${ldcextv} image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dgtk3-x11.sh
