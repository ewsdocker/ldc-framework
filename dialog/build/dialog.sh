#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dialog container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-dialog${ldcvers}${ldcextv}
docker rm ldc-dialog-dialog${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing dialog image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:dialog${ldcvers}${ldcextv}

# ===========================================================================
#
#    ldc-dialog:dialog${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:dialog${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="dialog" \
  \
  --build-arg DIALOG="DIALOG" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_DESKTOP="Dialog" \
  --build-arg BUILD_TEMPLATE="run" \
  --build-arg BUILD_PKG="cdialog (ComeOn Dialog) v. 1.3-20160828" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="dialog" \
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
  --file Dockerfile \
  \
  -t ewsdocker/ldc-dialog:dialog${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:dialog${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-dialog${ldcvers}${ldcextv} image built."
echo "   ****"
echo "   ***********************************************"
echo

. run/dialog.sh
