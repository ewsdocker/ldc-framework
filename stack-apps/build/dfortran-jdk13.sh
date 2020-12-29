#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/stack-apps

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dfortran-jdk13 container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv}
docker rm ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-stack-apps:dfortran-jdk13 image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv}"
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
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack-apps" \
  --build-arg FROM_VERS="djdk13-gtk3" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   ****  ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv} image created"
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
  -v ${HOME}/.config/docker/ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv}:/root \
  -v ${HOME}/.config/docker/ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv}/workspace:/workspace \
  \
  -e DISPLAY=unix${DISPLAY} \
  -v ${HOME}/.Xauthority:/root/.Xauthority \
  -v /tmp/.X11-unix:/tmp/.X11-unix \
  -v /dev/shm:/dev/shm \
  --device /dev/snd \
  \
  -v ${HOME}/Downloads:/Downloads \
  \
  --name=ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv} \
ewsdocker/ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-apps-dfortran-jdk13${ldcvers}${ldcextv} failed."
 }

echo "   ****************************************************"
echo "   ****"
echo "   **** ldc-stack-apps:dfortran-jdk13${ldcvers}${ldcextv} successfully installed."
echo "   ****"
echo "   ****************************************************"
echo

exit 0

