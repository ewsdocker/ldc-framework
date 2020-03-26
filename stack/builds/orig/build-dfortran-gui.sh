#!/bin/bash
# ===========================================================================
#
#    ldc-lang:dfortran-gui-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-lang

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dfortran-gui container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dfortran-gui-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dfortran-gui image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dfortran-gui-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dfortran-gui-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="FORTRAN" \
  \
  --build-arg CC_MULTI="0" \
  --build-arg CC_VER="6" \
  --build-arg CC_GUI="1" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-lang" \
  --build-arg BUILD_VERSION="dfortran-gui" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-lang" \
  --build-arg FROM_VERS="dcc-gui" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --network=pkgnet \
  \
  --file Dockerfile.dlang \
  -t ewsdocker/ldc-lang:dfortran-gui-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dfortran-gui-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dfortran-gui-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  --rm \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-lang-dfortran-gui-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-lang-dfortran-gui-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  --name=ldc-lang-dfortran-gui-0.1.0-b1 \
ewsdocker/ldc-lang:dfortran-gui-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dfortran-gui-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dfortran-gui-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dfortran-gui-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dfortran-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-lang:dfortran successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

