#!/bin/bash
# ===========================================================================
#
#    ldc-stack-apps:dgtk3-dcc-0.1.0-b4
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps:dgtk3-dcc container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-dgtk3-dcc-0.1.0-b4
docker rm ldc-stack-apps-dgtk3-dcc-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack-apps:dgtk3-dcc image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:dgtk3-dcc-0.1.0-b4

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:dgtk3-dcc-0.1.0-b4"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="GTK3" \
  --build-arg GTK_VER="GTK3" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="dgtk3-dcc" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b4" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dcc-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b4" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-stack-apps:dgtk3-dcc-0.1.0-b4 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:dgtk3-dcc-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** created ldc-stack-apps-dgtk3-dcc-0.1.0-b4 image"
echo "   ****"
echo "   ***********************************************"
echo

. run/dgtk3-dcc.sh
