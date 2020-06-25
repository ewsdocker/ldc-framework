#!/bin/bash
# ===========================================================================
#
#    ldc-stack-apps:dfortran-jdk13-0.1.0-b3
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dfortran-jdk13 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-stack-apps-dfortran-jdk13-0.1.0-b3

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack-apps:dfortran-jdk13 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:dfortran-jdk13-0.1.0-b3

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:dfortran-jdk13-0.1.0-b3"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
  --build-arg DNAME="FORT" \
  --build-arg FORTRAN_VER="6" \
  \
  --build-arg BUILD_DAEMON="1" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-stack-apps" \
  --build-arg BUILD_VERSION="dfortran-jdk13" \
  --build-arg BUILD_VERS_EXT="-0.1.0" \
  --build-arg BUILD_EXT_MOD="-b3" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-apps" \
  --build-arg FROM_VERS="djdk13-gtk3" \
  --build-arg FROM_EXT="-0.1.0" \
  --build-arg FROM_EXT_MOD="-b3" \
  \
  --build-arg LIB_INSTALL="0" \
  --build-arg LIB_VERSION="0.1.6" \
  --build-arg LIB_VERS_MOD="-b3" \
  \
  --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-stack-apps:dfortran-jdk13-0.1.0-b3 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:dfortran-jdk13-0.1.0-b3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-apps-dfortran-jdk13-0.1.0-b3"
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
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-stack-apps-dfortran-jdk13-0.1.0-b3:/root \
  -v ${HOME}/.config/docker/ldc-stack-apps-dfortran-jdk13-0.1.0-b3/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-stack-apps-dfortran-jdk13-0.1.0-b3 \
ewsdocker/ldc-stack-apps:dfortran-jdk13-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-apps-dfortran-jdk13-0.1.0-b3 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dfortran-jdk13-0.1.0-b3 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-apps-dfortran-jdk13-0.1.0-b3
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-apps-dfortran-jdk13-0.1.0-b3 failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-stack-apps:dfortran-jdk13-0.1.0-b3 successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

