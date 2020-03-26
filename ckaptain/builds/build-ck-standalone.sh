#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/ckaptain

declare version="0.1.0"

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ckaptain container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ckaptain-ckaptain-app-${version}
docker rm ldc-ckaptain-ckaptain-app-${version}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ckaptain-app image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-ckaptain:ckaptain-app-${version}

# ===========================================================================
#
#    ldc-ckaptain:ckaptain-app-${version}
#
# ===========================================================================

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-ckaptain:ckaptain-app-${version}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CKS" \
  \
  --build-arg RUN_APP="kaptain" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_DESKTOP="CKaptain App" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-ckaptain" \
  --build-arg BUILD_VERSION="ckaptain-app" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dqt4-x11" \
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
-t ewsdocker/ldc-ckaptain:kaptain-${version}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-ckaptain:ckaptain-app-${version} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ckaptain-ckaptain-app-${version}"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  -e LRUN_APP="/bin/bash" \
  --rm \
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
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain-app-${version}:/root \
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain-app-${version}/workspace:/workspace \
  \
  -v ${HOME}/.config/docker/ldc-ckaptain-ckaptain-app-${version}/repo:/repo \
  -v ${HOME}/Source:/source \
  -v ${HOME}/Development:/development \
  \
  --name=ldc-ckaptain-ckaptain-app-${version} \
ewsdocker/ldc-ckaptain:ckaptain-app-${version}
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-ckaptain-ckaptain-app-${version} failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-ckaptain:ckaptain-app-${version} successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

