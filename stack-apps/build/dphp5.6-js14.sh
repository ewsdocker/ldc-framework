#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:dphp5.6-js14${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dphp5.6-js14 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-dphp5.6-js14${ldcvers}${ldcextv}
docker rm ldc-stack-apps-dphp5.6-js14${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing dphp5.6-js14 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:dphp5.6-js14${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:dphp5.6-js14${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="PHP" \
  --build-arg PHP_VER="5.6" \
  --build-arg PHP_COMP="1" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="dphp5.6-js14" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-apps" \
  --build-arg FROM_VERS="djs14-jdk13" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-stack-apps:dphp5.6-js14${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:dphp5.6-js14${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-apps-dphp5.6-js14${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

. run/dphp5.6-js14.sh
