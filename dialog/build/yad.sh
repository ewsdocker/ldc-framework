#!/bin/bash
# ===========================================================================
#
#    ldc-dialog:yad-0.1.0-b4
#
# ===========================================================================

cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping yad container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-yad-0.1.0-b4
docker rm ldc-dialog-yad-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing yad image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:yad-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:yad-0.1.0-b4"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="yad" \
  \
  --build-arg DIALOG="YAD" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="gui" \
  --build-arg BUILD_DESKTOP="Yad GTK3" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="yad" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg BUILD_PKG="Yad v. 0.4.0" \
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
-t ewsdocker/ldc-dialog:yad-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:yad-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-yad-0.1.0-b4 image built."
echo "   ****"
echo "   ***********************************************"
echo

. run/yad.sh

