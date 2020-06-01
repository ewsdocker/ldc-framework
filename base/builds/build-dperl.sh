#!/bin/bash
# ===========================================================================
#
#    ldc-base:dperl-0.1.0-b2
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/base

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dperl-0.1.0-b2 2>null
docker rm ldc-base-dperl-0.1.0-b2 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-base:dperl image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-base:dperl-0.1.0-b2 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-base:dperl-0.1.0-b2 image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="PERL" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-base" \
  --build-arg BUILD_VERSION="dperl" \
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
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b2" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --build-arg GOSU_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
  -t ewsdocker/ldc-base:dperl-0.1.0-b2  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-base:dperl-0.1.0-b2 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dperl-0.1.0-b2 container"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  \
  -v /etc/localtime:/etc/localtime:ro \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-base-dperl-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-base-dperl-0.1.0/workspace:/workspace \
  \
  --name=ldc-base-dperl-0.1.0-b2 \
ewsdocker/ldc-base:dperl-0.1.0-b2
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-base-dperl-0.1.0-b2 failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl-0.1.0-b2 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dperl-0.1.0-b2
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dperl-0.1.0-b2 failed."
 }

docker rm ldc-base-dperl-0.1.0-b2
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dperl-0.1.0-b2 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dperl successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

