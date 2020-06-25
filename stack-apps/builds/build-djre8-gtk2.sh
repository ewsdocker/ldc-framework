#!/bin/bash
# ===========================================================================
#
#    ldc-stack-apps:djre8-gtk2-0.1.0-b3
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps:djre8-gtk2 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-stack-apps-djre8-gtk2-0.1.0-b3

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack-apps:djre8-gtk2 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:djre8-gtk2-0.1.0-b3

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:djre8-gtk2-0.1.0-b3"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="JDK" \
  --build-arg JDK_TYPE="jre8" \
  --build-arg JDK_VERS="8" \
  --build-arg JDK_RELEASE="jdk8" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="djre8-gtk2" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b3" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dgtk2" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b3" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b3" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-stack-apps:djre8-gtk2-0.1.0-b3 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:djre8-gtk2-0.1.0-b3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-apps-djre8-gtk2-0.1.0-b3"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
   -d \
   --rm \
   \
  -v /etc/localtime:/etc/localtime:ro \
  \
   -e LMS_BASE="${HOME}/.local" \
   -e LMS_HOME="${HOME}" \
   -e LMS_CONF="${HOME}/.config" \
   \
   -v ${HOME}/bin:/userbin \
   -v ${HOME}/.local:/usrlocal \
   -v ${HOME}/.config/docker:/conf \
   -v ${HOME}/.config/docker/ldc-stack-apps-djre8-gtk2-0.1.0:/root \
   -v ${HOME}/.config/docker/ldc-stack-apps-djre8-gtk2-0.1.0/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-stack-apps-djre8-gtk2-0.1.0-b3 \
 ewsdocker/ldc-stack-apps:djre8-gtk2-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-apps-djre8-gtk2-0.1.0-b3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-djre8-gtk2-0.1.0-b3 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-apps-djre8-gtk2-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-apps-djre8-gtk2-0.1.0-b3 failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-stack-apps:djre8-gtk2-0.1.0-b3 successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

