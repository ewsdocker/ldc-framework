#!/bin/bash
# ===========================================================================
#
#  dcc - debian C Runitime Dependencies
#
#    ldc-stack:dcc-0.1.0-b1
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/ldc-stack

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack:dcc container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-stack-dcc-0.1.0-b1

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack:dcc image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack:dcc-0.1.0-b1

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack:dcc-0.1.0-b1"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CPP" \
  \
  --build-arg CC_VER="6" \
  --build-arg CC_TYPE="CC" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="daemon" \
  \
  --build-arg BUILD_NAME="ldc-stack" \
  --build-arg BUILD_VERSION="dcc" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b1" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-base" \
  --build-arg FROM_VERS="dx11-base" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b1" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b1" \
  --build-arg LIB_HOST="http://alpine-nginx-pkgcache" \
  \
  --network=pkgnet \
  \
  --file Dockerfile.dstack \
  -t ewsdocker/ldc-stack:dcc-0.1.0-b1 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack:dcc-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-dcc-0.1.0-b1"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  --rm \
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
  -v ${HOME}/.config/docker/ldc-stack-dcc-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-stack-dcc-0.1.0/workspace:/workspace \
  \
  --name=ldc-stack-dcc-0.1.0-b1 \
ewsdocker/ldc-stack:dcc-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-dcc-0.1.0-b1 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dcc-0.1.0-b1 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-dcc-0.1.0-b1
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-dcc-0.1.0-b1 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-stack:dcc successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

