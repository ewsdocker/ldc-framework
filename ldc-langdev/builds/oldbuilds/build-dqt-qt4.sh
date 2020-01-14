#!/bin/bash
# ===========================================================================
#
#    ldc-lang:dqt-qt4-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-lang

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dqt container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dqt-qt4-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dqt image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dqt-qt4-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dqt-qt4-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg BUILD_QT4="1" \
   --build-arg BUILD_QT5="0" \
   \
   --build-arg BUILD_NAME="ldc-lang" \
   --build-arg BUILD_VERSION="dqt-qt4" \
   --build-arg BUILD_VERS_EXT="-0.1.0" \
   --build-arg BUILD_EXT_MOD="-b1" \
   \
   --build-arg FROM_REPO="ewsdocker" \
   --build-arg FROM_PARENT="ldc-lang" \
   --build-arg FROM_VERS="dcc-gpp6gui" \
   --build-arg FROM_EXT="-0.1.0" \
   --build-arg FROM_EXT_MOD="-b1" \
   \
   --build-arg LIB_INSTALL="0" \
   --build-arg LIB_VERSION="0.1.6" \
   --build-arg LIB_VERS_MOD="-b1" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.dqt \
   -t ewsdocker/ldc-lang:dqt-qt4-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dqt-qt4-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dqt-qt4-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  -e LMSOPT_QUIET="0" \
  --rm \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-lang-dqt-qt4-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-lang-dqt-qt4-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-lang-dqt-qt4-0.1.0-b1 \
 ewsdocker/ldc-lang:dqt-qt4-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dqt-qt4-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dqt-qt4-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dqt-qt4-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dqt-qt4-0.1.0-b1 failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-lang:dqt-qt4-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

