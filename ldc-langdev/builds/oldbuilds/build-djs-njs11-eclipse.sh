#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping djs container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-djs-0.1.0-njs11-eclipse

echo "   ********************************************"
echo "   ****"
echo "   **** removing djs image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:djs-0.1.0-njs11-eclipse

# ===========================================================================
#
#    ldc-lang:djs-0.1.0-njs11-eclipse
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:djs-0.1.0-njs11-eclipse"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
   --build-arg NODEJS_VER=11 \
   --build-arg NODEJS_HOST=http://alpine-nginx-pkgcache \
   \
   --build-arg FROM_VERS="djdk" \
   --build-arg FROM_PARENT="ldc-lang" \
   --build-arg FROM_EXT_MOD="-jre11-eclipse" \
   \
   --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
   --network=pkgnet \
   \
   --file Dockerfile.djs \
 -t ewsdocker/ldc-lang:djs-0.1.0-njs11-eclipse .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:djs-0.1.0-njs11-eclipse failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-djs-0.1.0-njs11-eclipse"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
  -e LMSOPT_QUIET="0" \
  --rm \
  \
  -e LMS_BASE="${HOME}/.local" \
  -e LMS_HOME="${HOME}" \
  -e LMS_CONF="${HOME}/.config" \
  \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-lang-djs-0.1.0-njs11-eclipse:/root \
  -v ${HOME}/.config/docker/ldc-lang-djs-0.1.0-njs11-eclipse/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-lang-djs-0.1.0-njs11-eclipse \
 ewsdocker/ldc-lang:djs-0.1.0-njs11-eclipse
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-djs-0.1.0-njs11-eclipse failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-djs-0.1.0-njs11-eclipse daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-djs-0.1.0-njs11-eclipse
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-djs-0.1.0-njs11-eclipse failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-lang:djs-0.1.0-njs11-eclipse successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

