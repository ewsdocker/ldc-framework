#!/bin/bash
# ===========================================================================
#
#    ldc-langdev:djava-jdk8-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-langdev

echo "   ********************************************"
echo "   ****"
echo "   **** stopping djava container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-langdev-djava-jdk8-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing djava image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-langdev:djava-jdk8-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-langdev:djava-jdk8-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg JDK_HOST=http://alpine-nginx-pkgcache \
   --build-arg JDK_RELEASE="jdk8" \
   --build-arg JDK_VERS="8" \
   \
   --build-arg BUILD_JDK="jdk8" \
   \
   --build-arg BUILD_NAME="ldc-langdev" \
   --build-arg BUILD_VERSION="djava-jdk8" \
   --build-arg BUILD_VERS_EXT="-0.1.0" \
   --build-arg BUILD_EXT_MOD="-b1" \
   \
   --build-arg FROM_PARENT="ldc-langdev" \
   --build-arg FROM_VERS="dcc-gpp6gui" \
   --build-arg FROM_EXT="-0.1.0" \
   --build-arg FROM_EXT_MOD="-b1" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.djava \
 -t ewsdocker/ldc-langdev:djava-jdk8-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-langdev:djava-jdk8-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-langdev-djava-jdk8-0.1.0-b1"
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
  -v ${HOME}/.config/docker/ldc-langdev-djava-jdk8-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-langdev-djava-jdk8-0.1.0/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-langdev-djava-jdk8-0.1.0-b1 \
ewsdocker/ldc-langdev:djava-jdk8-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-langdev-djava-jdk8-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-langdev-djava-jdk8-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-langdev-djava-jdk8-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-langdev-djava-jdk8-0.1.0-b1 failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-langdev:djava-jdk8-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

