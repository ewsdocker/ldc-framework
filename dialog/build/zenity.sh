#!/bin/bash
# ===========================================================================
#
#    ldc-dialog:zenity-0.1.0-b4
#
# ===========================================================================

cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping zenity container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-zenity-0.1.0-b4
docker rm ldc-dialog-zenity-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing zenity image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:zenity-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:zenity-0.1.0-b4"
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
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg BUILD_PKG="Zenity v. 3.22.0" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dgtk3-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-dialog:zenity-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:zenity-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-zenity-0.1.0-b4 image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/zenity.sh

