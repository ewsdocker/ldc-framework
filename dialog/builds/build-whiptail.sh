#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-dialog-whiptail container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-whiptail-0.1.0-b1
docker rm ldc-dialog-whiptail-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-dialog:whiptail image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:whiptail-0.1.0-b1

# ===========================================================================
#
#    ldc-dialog:whiptail-0.1.0-b1
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:whiptail-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="whiptail" \
  \
  --build-arg DIALOG="WHIP" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="run" \
  --build-arg BUILD_DESKTOP="WhipTail" \
  --build-arg BUILD_PKG="Whiptail v. 0.52.19" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="whiptail" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-dialog:whiptail-0.1.0-b1  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:whiptail-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-whiptail-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  --rm \
  -v /etc/localtime:/etc/localtime:ro \
  \
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
  -v ${HOME}/.config/docker/ldc-dialog-whiptail-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-dialog-whiptail-0.1.0/workspace:/workspace \
  \
  -v ${HOME}/Source:/source \
  -v ${HOME}/Documents:/documents \
  -v ${HOME}/Development:/development \
  \
  --name=ldc-dialog-whiptail-0.1.0-b1 \
ewsdocker/ldc-dialog:whiptail-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-whiptail-0.1.0-b1 failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:whiptail-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

