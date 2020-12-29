#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-dialog:zenity${ldcvers}${ldcextv}
#
# ===========================================================================

cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping zenity container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-zenity${ldcvers}${ldcextv}
docker rm ldc-dialog-zenity${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing zenity image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:zenity${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:zenity${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="zenity" \
  \
  --build-arg DIALOG="ZENITY" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="gui" \
  --build-arg BUILD_DESKTOP="Zenity GTK3" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="zenity" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg BUILD_PKG="Zenity v. 3.22.0" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dgtk3-x11" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network="${pkgnet}" \
  \
  --file Dockerfile \
-t ewsdocker/ldc-dialog:zenity${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:zenity${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-zenity${ldcvers}${ldcextv} image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/zenity.sh

