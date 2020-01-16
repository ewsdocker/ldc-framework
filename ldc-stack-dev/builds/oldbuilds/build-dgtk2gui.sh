#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping dgtk container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dgtk-0.1.0-gtk2gui

echo "   ********************************************"
echo "   ****"
echo "   **** removing dgtk image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dgtk-0.1.0-gtk2gui

# ===========================================================================
#
#    ldc-lang:dgtk-0.1.0-gtk2gui
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dgtk-0.1.0-gtk2gui"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg BUILD_EXT_MOD="-gtk2gui" \
   --build-arg BUILD_GTK2="1" \
   \
   --build-arg FROM_PARENT="ldc-core" \
   --build-arg FROM_VERS="dgui" \
   --build-arg FROM_EXT="-0.1.0" \
   --build-arg FROM_EXT_MOD="" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.dgtk \
 -t ewsdocker/ldc-lang:dgtk-0.1.0-gtk2gui .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dgtk-0.1.0-gtk2gui failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dgtk-0.1.0-gtk2gui"
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
   -v ${HOME}/.config/docker/ldc-lang-dgtk-0.1.0-gtk2gui:/root \
   -v ${HOME}/.config/docker/ldc-lang-dgtk-0.1.0-gtk2gui/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-lang-dgtk-0.1.0-gtk2gui \
 ewsdocker/ldc-lang:dgtk-0.1.0-gtk2gui
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dgtk-0.1.0-gtk2gui failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dgtk-0.1.0-gtk2gui daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dgtk-0.1.0-gtk2gui
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dgtk-0.1.0-gtk2gui failed."
 }

echo "   ******************************************************"
echo "   ****"
echo "   **** ldc-lang:dgtk-0.1.0-gtk2gui successfully installed."
echo "   ****"
echo "   ******************************************************"
echo

exit 0

