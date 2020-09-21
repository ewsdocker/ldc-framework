#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dialog container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-dialog-0.1.0-b4
docker rm ldc-dialog-dialog-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing dialog image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:dialog-0.1.0-b4

# ===========================================================================
#
#    ldc-dialog:dialog-0.1.0-b4
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:dialog-0.1.0-b4"
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
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  \
  --network=pkgnet \
  --file Dockerfile \
  \
  -t ewsdocker/ldc-dialog:dialog-0.1.0-b4  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:dialog-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-dialog-0.1.0-b4 image built."
echo "   ****"
echo "   ***********************************************"
echo

. run/dialog.sh
