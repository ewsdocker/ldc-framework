#!/bin/bash
# ===========================================================================
#
#    ldc-lang:dperl-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-lang

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-lang:dperl container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-lang-dperl-0.1.0-b1 2>null
docker rm ldc-lang-dperl-0.1.0-b1 2>null

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-lang:dperl image"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dperl-0.1.0-b1 

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dperl-0.1.0-b1 image "
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="PERL" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-lang" \
  --build-arg BUILD_VERSION="dperl" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dbase-perl" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --build-arg GOSU_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile.dlang \
  -t ewsdocker/ldc-lang:dperl-0.1.0-b1  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dperl-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dperl-0.1.0-b1 container"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-lang-dperl-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-lang-dperl-0.1.0/workspace:/workspace \
  \
  --name=ldc-lang-dperl-0.1.0-b1 \
ewsdocker/ldc-lang:dperl-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dperl-0.1.0-b1 failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dperl-0.1.0-b1 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-lang-dperl-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dperl-0.1.0-b1 failed."
 }

docker rm ldc-lang-dperl-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-lang-dperl-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-lang:dperl successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

