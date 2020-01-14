#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping dphp container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dphp-0.1.0-5.6-eclipse

echo "   ********************************************"
echo "   ****"
echo "   **** removing dphp image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dphp-0.1.0-5.6-eclipse

# ===========================================================================
#
#    ldc-lang:dphp-0.1.0-5.6-eclipse
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dphp-0.1.0-5.6-eclipse"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg FROM_VERS="djdk" \
   --build-arg FROM_PARENT="ldc-lang" \
   --build-arg FROM_EXT_MOD="-jre11-eclipse" \
   \
   --build-arg PHP_VER="5.6" \
   --build-arg PHP_COMP="1" \
   \
   --build-arg BUILD_EXT_MOD="-5.6-eclipse" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.dphp \
 -t ewsdocker/ldc-lang:dphp-0.1.0-5.6-eclipse .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dphp-0.1.0-5.6-eclipse failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dphp-0.1.0-5.6-eclipse"
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
   -v ${HOME}/.config/docker/ldc-lang-dphp-0.1.0-5.6-eclipse:/root \
   -v ${HOME}/.config/docker/ldc-lang-dphp-0.1.0-5.6-eclipse/workspace:/workspace \
   \
   -e DISPLAY=unix${DISPLAY} \
   -v ${HOME}/.Xauthority:/root/.Xauthority \
   -v /tmp/.X11-unix:/tmp/.X11-unix \
   -v /dev/shm:/dev/shm \
   --device /dev/snd \
   \
   -v ${HOME}/Downloads:/Downloads \
   \
   --name=ldc-lang-dphp-0.1.0-5.6-eclipse \
 ewsdocker/ldc-lang:dphp-0.1.0-5.6-eclipse
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dphp-0.1.0-5.6-eclipse failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dphp-0.1.0-5.6-eclipse daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dphp-0.1.0-5.6-eclipse
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dphp-0.1.0-5.6-eclipse failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-lang:dphp-0.1.0-5.6-eclipse successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

