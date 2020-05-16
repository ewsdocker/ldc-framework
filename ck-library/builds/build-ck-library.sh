#!/bin/bash
cd ~/Development/ewsldc/ldc-framework/ck-library

# ===========================================================================
#
#    ldc-ckaptain:ck-library-0.1.0-b1
#
# ===========================================================================

#declare version="0.1.0-b1"

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ckaptain:ck-library container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ckaptain-ck-library-0.1.0-b1
docker rm ldc-ckaptain-ck-library-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-ckaptain-ck-library image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-ckaptain:ck-library-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ldc-ckaptain:ck-library-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CK-LIB" \
  --build-arg QT_VER="QT4" \
  \
  --build-arg RUN_APP="ck-archive" \
  \
  --build-arg KAPTAIN_HOST="http://alpine-nginx-pkgcache" \
  --build-arg KAPTAIN_VERS="0.8" \
  \
  --build-arg BUILD_PKG="Kaptain v. 0.8" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_DESKTOP="CK-Lib" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-ckaptain" \
  --build-arg BUILD_VERSION="ck-library" \
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
  --build-arg CKLIB_HOST="http://alpine-nginx-pkgcache" \
  --build-arg CKLIB_DIR="/ckaptain-lib" \
  --build-arg CKLIB_VERS="0.1.0" \
  --build-arg CKLIB_VERSX="-b1" \
  --build-arg CKLIB_RELEASE="v.0" \
  --build-arg CKLIB_NAME="ckaptain-lib" \
  --build-arg CKLIB_DEST="/repo" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-ckaptain:ck-library-0.1.0-b1  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-ckaptain:ck-library-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-ckaptain-ck-library-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -it \
  -e LRUN_APP="ck-archive" \
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
  -v ${HOME}/.config/docker/ldc-ckaptain-ck-library-0.1.0-b1:/root \
  -v ${HOME}/.config/docker/ldc-ckaptain-ck-library-0.1.0-b1/workspace:/workspace \
  \
  --mount source=pkgcache,target=/repo \
  \
  -v ${HOME}/Source:/source \
  \
  --name=ldc-ckaptain-ck-library-0.1.0-b1 \
ewsdocker/ldc-ckaptain:ck-library-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "create container ldc-ckaptain-ck-library-0.1.0-b1 failed."
 	exit 1
 }

echo
echo "   ****************************************************************"
echo "   ****"
echo "   **** ldc-ckaptain:ck-library-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************************"
echo
echo

exit 0

