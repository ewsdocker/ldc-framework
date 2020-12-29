#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dkaptain container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-kaptain${ldcvers}${ldcextv}
docker rm ldc-dialog-kaptain${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing dkaptain image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:kaptain${ldcvers}${ldcextv}

# ===========================================================================
#
#    ldc-dialog:kaptain${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:kaptain${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="kaptain" \
  \
  --build-arg DIALOG="KAPTAIN" \
  \
  --build-arg KAPTAIN_VERS="0.8" \
  --build-arg BUILD_PKG="Kaptain v. 0.1.0" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="run" \
  --build-arg BUILD_DESKTOP="Kaptain" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="kaptain" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dqt4-x11" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --build-arg KAPTAIN_HOST="${pkgserver}"\
  --network="${pkgnet}" \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-dialog:kaptain${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:kaptain${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-kaptain${ldcvers}${ldcextv} image built."
echo "   ****"
echo "   ***********************************************"
echo

. run/kaptain.sh
