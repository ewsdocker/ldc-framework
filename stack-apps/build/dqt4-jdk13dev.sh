#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dqt4-jdk13dev container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}
docker rm ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack-apps:dqt4-jdk13dev image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="CPP" \
  --build-arg FORTRAN_VER="6" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="dqt4-jdk13dev" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-apps" \
  --build-arg FROM_VERS="djdk13dev-gtk3" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network="${pkgnet}" \
  \
  --file Dockerfile \
-t ewsdocker/ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:dqt4-jdk13dev${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-apps-dqt4-jdk13dev${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***********************************************"
echo

. run/dqt4-jdk13dev.sh

