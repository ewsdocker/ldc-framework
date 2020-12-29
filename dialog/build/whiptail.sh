#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh


# ===========================================================================
#
#    ldc-dialog:whiptail${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-dialog-whiptail container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-whiptail${ldcvers}${ldcextv}
docker rm ldc-dialog-whiptail${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-dialog:whiptail image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:whiptail${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:whiptail${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="whiptail" \
  \
  --build-arg DIALOG="WHIP" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="run" \
  --build-arg BUILD_DESKTOP="WhipTail" \
  --build-arg BUILD_PKG="Whiptail v. 0.52.19" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="whiptail" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network="${pkgnet}" \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-dialog:whiptail${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:whiptail${ldcvers}${ldcextv} failed."
 	exit 1
 }

. run/whiptail.sh
