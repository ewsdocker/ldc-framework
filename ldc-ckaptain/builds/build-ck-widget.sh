#!/bin/bash
# ===========================================================================
#
#    ldc-ckaptain:ck-widget-${version}
#
# ===========================================================================

cd ~/Development/ewsldc/ldc-ckaptain

declare version="0.1.0-b1"

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ckaptain-ck-widget container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ckaptain-ck-widget-${version}
docker rm ldc-ckaptain-ck-widget-${version}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ck-widget image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-ckaptain:ck-widget-${version}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-ckaptain:ck-widget-${version}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
      --build-arg DNAME="CKW" \
      \
      --build-arg RUN_APP="ck-widget" \
      \
      --build-arg BUILD_DAEMON="0" \
      --build-arg BUILD_DESKTOP="CKaptain Widget" \
      --build-arg BUILD_TEMPLATE="gui" \
      \
      --build-arg BUILD_NAME="ldc-ckaptain" \
      --build-arg BUILD_VERSION="ck-widget" \
      --build-arg BUILD_VERS_EXT="-0.1.0" \
      --build-arg BUILD_EXT_MOD="-b1" \
      \
      --build-arg FROM_REPO="ewsdocker" \
      --build-arg FROM_PARENT="ldc-ckaptain" \
      --build-arg FROM_VERS="ck-stack-qt4" \
      --build-arg FROM_EXT="-0.1.0" \
      --build-arg FROM_EXT_MOD="-b1" \
      \
      --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
      --build-arg LIB_INSTALL="0" \
      --build-arg LIB_VERSION="0.1.6" \
      --build-arg LIB_VERS_MOD="-b1" \
      \
      --network=pkgnet \
      \
      --file Dockerfile \
-t ewsdocker/ldc-ckaptain:ck-widget-${version}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-ckaptain:ck-widget-${version} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ckaptain-ck-widget-${version}"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
      -it \
      --rm \
      -e LRUN_APP="/bin/bash" \
      \
      --network=pkgnet \
      \
      -e LMS_BASE="${HOME}/.local" \
      -e LMS_HOME="${HOME}" \
      -e LMS_CONF="${HOME}/.config" \
      \
      -v ${HOME}/bin:/userbin \
      -v ${HOME}/.local:/usrlocal \
      -v ${HOME}/.config/docker:/conf \
      -v ${HOME}/.config/docker/ldc-ckaptain-ck-widget-${version}:/root \
      -v ${HOME}/.config/docker/ldc-ckaptain-ck-widget-${version}/workspace:/workspace \
      \
      -v ${HOME}/.config/docker/ldc-ckaptain-ck-widget-${version}/repo:/repo \
      -v ${HOME}/Source:/source \
      -v ${HOME}/Development:/development \
      \
      -v /etc/localtime:/etc/localtime:ro \
      \
      -e DISPLAY=unix${DISPLAY} \
      -v ${HOME}/.Xauthority:/root/.Xauthority \
      -v /tmp/.X11-unix:/tmp/.X11-unix \
      -v /dev/shm:/dev/shm \
      --device /dev/snd \
      \
      --name=ldc-ckaptain-ck-widget-${version} \
    ewsdocker/ldc-ckaptain:ck-widget-${version}
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-ckaptain-ck-widget-${version} failed."
 	exit 2
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-ckaptain:ck-widget-${version} successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

