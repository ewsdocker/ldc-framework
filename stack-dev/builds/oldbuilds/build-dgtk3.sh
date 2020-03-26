#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping dgtk container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dgtk-0.1.0-gtk3

echo "   ********************************************"
echo "   ****"
echo "   **** removing dgtk image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dgtk-0.1.0-gtk3

# ===========================================================================
#
#    ldc-lang:dgtk-0.1.0-gtk3
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dgtk-0.1.0-gtk3"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
      --build-arg BUILD_EXT_MOD="-gtk3" \
      --build-arg BUILD_GTK3="1" \
      \
      --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
      --network=pkgnet \
      \
      --file Dockerfile.dgtk \
      -t ewsdocker/ldc-lang:dgtk-0.1.0-gtk3 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dgtk-0.1.0-gtk3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dgtk-0.1.0-gtk3"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
   -d \
   --rm \
   \
   -e LMS_BASE="/root/.local" \
   -e LMS_HOME="/root" \
   -e LMS_CONF="/root/.config" \
   \
   -v ${HOME}/bin:/userbin \
   -v ${HOME}/.local:/usrlocal \
   -v ${HOME}/.config/docker:/conf \
   -v ${HOME}/.config/docker/ldc-lang-dgtk-0.1.0-gtk3:/root \
   -v ${HOME}/.config/docker/ldc-lang-dgtk-0.1.0-gtk3/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-lang-dgtk-0.1.0-gtk3 \
  ewsdocker/ldc-lang:dgtk-0.1.0-gtk3
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dgtk-0.1.0-gtk3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dgtk-0.1.0-gtk3 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dgtk-0.1.0-gtk3
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dgtk-0.1.0-gtk3 failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-lang:dgtk-0.1.0-gtk3 successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

