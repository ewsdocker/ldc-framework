#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping dphp container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dphp-0.1.0-php5.6

echo "   ********************************************"
echo "   ****"
echo "   **** removing dphp image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dphp-0.1.0-php5.6

# ===========================================================================
#
#    ldc-lang:dphp-0.1.0-php5.6
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dphp-0.1.0-php5.6"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg PHP_VER="5.6" \
   --build-arg PHP_COMP="1" \
   \
   --build-arg BUILD_EXT_MOD="php5.6" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.dphp \
 -t ewsdocker/ldc-lang:dphp-0.1.0-php5.6 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dphp-0.1.0-php5.6 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dphp-0.1.0-php5.6"
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
   -v ${HOME}/.config/docker/ldc-lang-dphp-0.1.0-php5.6:/root \
   -v ${HOME}/.config/docker/ldc-lang-dphp-0.1.0-php5.6/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-lang-dphp-0.1.0-php5.6 \
 ewsdocker/ldc-lang:dphp-0.1.0-php5.6
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dphp-0.1.0-php5.6 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dphp-0.1.0-php5.6 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dphp-0.1.0-php5.6
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dphp-0.1.0-php5.6 failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-lang:dphp-0.1.0-php5.6 successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

