#!/bin/bash
# ===========================================================================
#
#    ldc-langdev:dfortran-jdk8-gtk2-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-langdev

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dfortran-jdk8-gtk2 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dfortran-jdk8-gtk2 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-langdev:dfortran-jdk8-gtk2-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-langdev:dfortran-jdk8-gtk2-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg CPP_MULTI="0" \
  --build-arg CPP_VER="6" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-langdev" \
  --build-arg BUILD_VERSION="dfortran-jdk8-gtk2" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-langdev" \
  --build-arg FROM_VERS="djava-jdk8-gtk2" \
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
  --file Dockerfile.dfortran \
  -t ewsdocker/ldc-langdev:dfortran-jdk8-gtk2-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-langdev:dfortran-jdk8-gtk2-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1"
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
  -v ${HOME}/.config/docker/ldc-langdev-dfortran-jdk8-gtk2-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-langdev-dfortran-jdk8-gtk2-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  --name=ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1 \
ewsdocker/ldc-langdev:dfortran-jdk8-gtk2-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-langdev-dfortran-jdk8-gtk2-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-langdev:dfortran-jdk8-gtk2 successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

