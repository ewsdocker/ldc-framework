#!/bin/bash
# ===========================================================================
#
#    ldc-stack:dgtk2-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dgtk2 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dgtk2-0.1.0-b4
docker rm ldc-stack-dgtk2-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack:dgtk2 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack:dgtk2-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack:dgtk2-0.1.0-b4"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="GTK" \
  --build-arg GTK_VER="GTK2" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack" \
  --build-arg BUILD_VERSION="dgtk2" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dcc-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --network=pkgnet\
  --file Dockerfile \
  \
-t ewsdocker/ldc-stack:dgtk2-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack:dgtk2-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** ldc-stack-dgtk2-0.1.0-b4 image created."
echo "   ****"
echo "   ***********************************************"
echo

. run/dgtk2.sh
