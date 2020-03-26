#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping yad container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-yad-0.1.0-b1
docker rm ldc-dialog-yad-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing yad image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:yad-0.1.0-b1

# ===========================================================================
#
#    ldc-dialog:yad-0.1.0-b1
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:yad-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="yad" \
  \
  --build-arg DIALOG="YAD" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="gui" \
  --build-arg BUILD_DESKTOP="Yad GTK3" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="yad" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg BUILD_PKG="Yad v. 0.4.0" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dgtk3-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-dialog:yad-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:yad-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-yad-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  --rm \
  -e LRUN_APP="/bin/bash" \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-dialog-yad-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-dialog-yad-0.1.0/workspace:/workspace \
  \
  -v ${HOME}/Source:/source \
  -v ${HOME}/Pictures:/pictures \
  -v ${HOME}/Documents:/documents \
  -v ${HOME}/Development:/development \
  \
  --name=ldc-dialog-yad-0.1.0-b1 \
ewsdocker/ldc-dialog:yad-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-yad-0.1.0-b1 failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:yad-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0
