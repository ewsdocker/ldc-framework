#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dkaptain container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-kaptain-0.1.0-b1
docker rm ldc-dialog-kaptain-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dkaptain image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:kaptain-0.1.0-b1

# ===========================================================================
#
#    ldc-dialog:kaptain-0.1.0-b1
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:kaptain-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="kaptain" \
  \
  --build-arg DIALOG="KAPTAIN" \
  \
  --build-arg KAPTAIN_VERS="0.8" \
  --build-arg BUILD_PKG="Kaptain v. 0.1.0" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="run" \
  --build-arg BUILD_DESKTOP="Kaptain" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="kaptain" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dqt4-x11" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --build-arg KAPTAIN_HOST="http://alpine-nginx-pkgcache"\
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-dialog:kaptain-0.1.0-b1  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:kaptain-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-kaptain-0.1.0-b1"
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
      -v ${HOME}/.config/docker/ldc-dialog-kaptain-0.1.0-b1:/root \
      -v ${HOME}/.config/docker/ldc-dialog-kaptain-0.1.0-b1/workspace:/workspace \
      \
      -v ${HOME}/Source:/source \
      -v ${HOME}/Pictures:/pictures \
      -v ${HOME}/Documents:/documents \
      -v ${HOME}/Development:/development \
      \
      --name=ldc-dialog-kaptain-0.1.0-b1 \
    ewsdocker/ldc-dialog:kaptain-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-kaptain-0.1.0-b1 failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:kaptain-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

