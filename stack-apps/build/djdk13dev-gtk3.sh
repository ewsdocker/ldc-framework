#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:djdk13dev-gtk3${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps:djdk13dev-gtk3 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-djdk13dev-gtk3${ldcvers}${ldcextv}
docker rm ldc-stack-apps-djdk13dev-gtk3${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack-apps:djdk13dev-gtk3 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:djdk13dev-gtk3${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:djdk13dev-gtk3${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="JDK" \
  --build-arg JDK_TYPE="jdk13" \
  \
  --build-arg JDK_VERS="13.0.1" \
  --build-arg JDK_RELEASE="jdk13" \
  --build-arg JDK_HOST="${pkgserver}" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="djdk13dev-gtk3" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-dev" \
  --build-arg FROM_VERS="dgtk3-dev" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network="${pkgnet}" \
  --file Dockerfile \
  -t ewsdocker/ldc-stack-apps:djdk13dev-gtk3${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:djdk13dev-gtk3${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-apps-djdk13dev-gtk3${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

. run/djdk13dev-gtk3.sh
