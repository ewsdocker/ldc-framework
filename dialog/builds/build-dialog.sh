#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/dialog

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dialog container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-dialog-dialog-0.1.0-b2
docker rm ldc-dialog-dialog-0.1.0-b2

echo "   ********************************************"
echo "   ****"
echo "   **** removing dialog image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-dialog:dialog-0.1.0-b2

# ===========================================================================
#
#    ldc-dialog:dialog-0.1.0-b2
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-dialog:dialog-0.1.0-b2"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg RUN_APP="dialog" \
  \
  --build-arg DIALOG="DIALOG" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_DESKTOP="Dialog" \
  --build-arg BUILD_TEMPLATE="run" \
  --build-arg BUILD_PKG="cdialog (ComeOn Dialog) v. 1.3-20160828" \
  \
  --build-arg BUILD_NAME="ldc-dialog" \
  --build-arg BUILD_VERSION="dialog" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b2" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b2" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b2" \
  \
  --network=pkgnet \
  --file Dockerfile \
  \
  -t ewsdocker/ldc-dialog:dialog-0.1.0-b2  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-dialog:dialog-0.1.0-b2 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-dialog-dialog-0.1.0-b2"
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
      -v ${HOME}/bin:/userbin \
      -v ${HOME}/.local:/usrlocal \
      -v ${HOME}/.config/docker:/conf \
      -v ${HOME}/.config/docker/ldc-dialog-dialog-0.1.0:/root \
      -v ${HOME}/.config/docker/ldc-dialog-dialog-0.1.0/workspace:/workspace \
      \
      -v ${HOME}/Source:/source \
      -v ${HOME}/Documents:/documents \
      -v ${HOME}/Development:/development \
      \
      --name=ldc-dialog-dialog-0.1.0-b2 \
    ewsdocker/ldc-dialog:dialog-0.1.0-b2
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-dialog-dialog-0.1.0-b2 failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-dialog:dialog-0.1.0-b2 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

