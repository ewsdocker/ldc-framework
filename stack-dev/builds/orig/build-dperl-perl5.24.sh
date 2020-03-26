#!/bin/bash
# ===========================================================================
#
#    ldc-langdev:dperl-perl5.24-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-langdev

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dperl-perl5.24 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-langdev-dperl-perl5.24-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing dperl-perl5.24 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-langdev:dperl-perl5.24-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-langdev:dperl-perl5.24-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg PERL_REL="5.0" \
   --build-arg PERL_VER="5.24.1-3" \
   --build-arg PERL_HOST=http://alpine-nginx-pkgcache \
   --build-arg PERL_BUILD="1" \
   \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-langdev" \
  --build-arg BUILD_VERSION="dperl-perl5.24" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-langdev" \
  --build-arg FROM_VERS="dcc-gpp6gui" \
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
   --file Dockerfile.dperl \
 -t ewsdocker/ldc-langdev:dperl-perl5.24-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-langdev:dperl-perl5.24-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-langdev-dperl-perl5.24-0.1.0-b1"
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
   -v ${HOME}/.config/docker/ldc-langdev-dperl-perl5.24-0.1.0-b1:/root \
   -v ${HOME}/.config/docker/ldc-langdev-dperl-perl5.24-0.1.0-b1/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-langdev-dperl-perl5.24-0.1.0-b1 \
 ewsdocker/ldc-langdev:dperl-perl5.24-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-langdev-dperl-perl5.24-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-langdev-dperl-perl5.24-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-langdev-dperl-perl5.24-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-langdev-dperl-perl5.24-0.1.0-b1 failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-langdev:dperl-perl5.24-0.1.0-b1 successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

