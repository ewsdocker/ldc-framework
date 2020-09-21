#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dkaptain container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-kaptain-0.1.0-b4
docker rm ldc-dialog-kaptain-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing dkaptain image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:kaptain-0.1.0-b4

# ===========================================================================
#
#    ldc-dialog:kaptain-0.1.0-b4
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:kaptain-0.1.0-b4"
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
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dqt4-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b4" \
  \
  --build-arg KAPTAIN_HOST="http://alpine-nginx-pkgcache"\
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-dialog:kaptain-0.1.0-b4  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:kaptain-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-dialog-kaptain-0.1.0-b4 image built."
echo "   ****"
echo "   ***********************************************"
echo

. run/kaptain.sh
