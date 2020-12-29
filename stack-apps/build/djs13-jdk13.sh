#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:djs13-jdk13${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping djs13-jdk13 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-djs13-jdk13${ldcvers}${ldcextv}
docker rm ldc-stack-apps-djs13-jdk13${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing djs13-jdk13 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:djs13-jdk13${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:djs13-jdk13${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="NJS" \
  --build-arg NODEJS_HOST="${pkgserver}" \
  --build-arg NODEJS_VER="13" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="djs13-jdk13" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_PARENT="ldc-stack-apps" \
  --build-arg FROM_VERS="djdk13-gtk3" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network="${pkgnet}" \
  \
  --file Dockerfile \
-t ewsdocker/ldc-stack-apps:djs13-jdk13${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:djs13-jdk13${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-apps-djs13-jdk13${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

. run/djs13-jdk13.sh
