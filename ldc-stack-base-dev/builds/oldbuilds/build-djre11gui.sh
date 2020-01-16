#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping djdk container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-djdk-0.1.0-jre11gui

echo "   ********************************************"
echo "   ****"
echo "   **** removing djdk image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:djdk-0.1.0-jre11gui

# ===========================================================================
#
#    ldc-lang:djdk-0.1.0-jre11gui
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:djdk-0.1.0-jre11gui"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg JDK_HOST=http://alpine-nginx-pkgcache \
   --build-arg JDK_RELEASE="jdk11" \
   --build-arg JDK_VERS="11.0.2" \
   \
   --build-arg BUILD_JRE="jre11" \
   \
   --build-arg FROM_PARENT="ldc-core" \
   --build-arg FROM_VERS="dgui" \
   --build-arg FROM_EXT="-0.1.0" \
   --build-arg FROM_EXT_MOD="" \
   \
   --build-arg BUILD_NAME="ldc-lang" \
   --build-arg BUILD_VERSION="djdk" \
   --build-arg BUILD_VERS_EXT="-0.1.0" \
   --build-arg BUILD_EXT_MOD="-jre11gui" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.djdk \
 -t ewsdocker/ldc-lang:djdk-0.1.0-jre11gui .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:djdk-0.1.0-jre11gui failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-djdk-0.1.0-jre11gui"
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
  -v ${HOME}/.config/docker/ldc-lang-djdk-0.1.0-jre11gui:/root \
  -v ${HOME}/.config/docker/ldc-lang-djdk-0.1.0-jre11gui/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-lang-djdk-0.1.0-jre11gui \
ewsdocker/ldc-lang:djdk-0.1.0-jre11gui
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-djdk-0.1.0-jre11gui failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-djdk-0.1.0-jre11gui daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-djdk-0.1.0-jre11gui
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-djdk-0.1.0-jre11gui failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-lang:djdk-0.1.0-jre11gui successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

