#!/bin/bash
# ===========================================================================
#
#    ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/ck-library


echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ckaptain-ck-stack-qt4-gtk3 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing ewsdocker/ldc-ckaptain:ck-stack-qt4-gtk3 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="CK-STACK" \
  --build-arg QT_VER="QT4" \
  --build-arg RUN_APP="bash" \
  \
  --build-arg KAPTAIN_HOST="http://alpine-nginx-pkgcache" \
  --build-arg KAPTAIN_VERS="0.8" \
  \
  --build-arg BUILD_PKG="Kaptain v. 0.8" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_DESKTOP="CKaptain" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-ckaptain" \
  --build-arg BUILD_VERSION="ck-stack-qt4-gtk3" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dgtk3-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  \
  --network=pkgnet\
  --file Dockerfile \
  \
-t ewsdocker/ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
   -d \
   -it \
   \
   -v /etc/localtime:/etc/localtime:ro \
   \
   -e LMS_BASE="/root/.local" \
   -e LMS_HOME="/root" \
   -e LMS_CONF="/root/.config" \
   \
   -v ${HOME}/bin:/userbin \
   -v ${HOME}/.local:/usrlocal \
   -v ${HOME}/.config/docker:/conf \
   -v ${HOME}/.config/docker/ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0:/root \
   -v ${HOME}/.config/docker/ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1 \
 ewsdocker/ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-ckaptain-ck-stack-qt4-gtk3-0.1.0-b1 failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-ckaptain:ck-stack-qt4-gtk3-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

