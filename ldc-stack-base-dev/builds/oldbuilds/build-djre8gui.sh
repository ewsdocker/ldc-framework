#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping djdk container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-djdk-0.1.0-jre8gui

echo "   ********************************************"
echo "   ****"
echo "   **** removing djdk image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:djdk-0.1.0-jre8gui

# ===========================================================================
#
#    ldc-lang:djdk-0.1.0-jre8gui
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:djdk-0.1.0-jre8gui"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg JDK_RELEASE="jdk8" \
   \
   --build-arg BUILD_JRE="jre8" \
   \
   --build-arg FROM_PARENT="ldc-core" \
   --build-arg FROM_VERS="dgui" \
   --build-arg FROM_EXT="-0.1.0" \
   --build-arg FROM_EXT_MOD="" \
   \
   --build-arg BUILD_NAME="ldc-lang" \
   --build-arg BUILD_VERSION="djdk" \
   --build-arg BUILD_VERS_EXT="-0.1.0" \
   --build-arg BUILD_EXT_MOD="-jre8gui" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.djdk \
   -t ewsdocker/ldc-lang:djdk-0.1.0-jre8gui .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:djdk-0.1.0-jre8gui failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-djdk-0.1.0-jre8gui"
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
   -v ${HOME}/.config/docker/ldc-lang-djdk-0.1.0-jre8gui:/root \
   -v ${HOME}/.config/docker/ldc-lang-djdk-0.1.0-jre8gui/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-lang-djdk-0.1.0-jre8gui \
 ewsdocker/ldc-lang:djdk-0.1.0-jre8gui
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-djdk-0.1.0-jre8gui failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-djdk-0.1.0-jre8gui daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-djdk-0.1.0-jre8gui
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-djdk-0.1.0-jre8gui failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-lang:djdk-0.1.0-jre8gui successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

