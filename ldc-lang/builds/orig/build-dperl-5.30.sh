#!/bin/bash
# ===========================================================================
#
#    ldc-lang:dperl-5.30-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-lang

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dperl-5.30 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dperl-5.30-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dperl-5.30 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dperl-5.30-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dperl-5.30-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="PERL" \
  --build-arg PERL_VER="5.30.1" \
  --build-arg PERL_REL="5.0" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-lang" \
  --build-arg BUILD_VERSION="dperl-5.30" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-lang" \
  --build-arg FROM_VERS="dcc" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile.dlang \
-t ewsdocker/ldc-lang:dperl-5.30-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dperl-5.30-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dperl-5.30-0.1.0-b1"
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
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-lang-dperl-5.30-0.1.0-b1:/root \
  -v ${HOME}/.config/docker/ldc-lang-dperl-5.30-0.1.0-b1/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-lang-dperl-5.30-0.1.0-b1 \
ewsdocker/ldc-lang:dperl-5.30-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dperl-5.30-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dperl-5.30-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dperl-5.30-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dperl-5.30-0.1.0-b1 failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-lang:dperl-5.30-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

