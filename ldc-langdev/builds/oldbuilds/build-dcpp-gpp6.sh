#!/bin/bash
echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcpp container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-lang-dcpp-0.1.0-gpp6

echo "   ********************************************"
echo "   ****"
echo "   **** removing dcpp image(s)"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-lang:dcpp-0.1.0-gpp6

# ===========================================================================
#
#    ldc-lang:dcpp-0.1.0-gpp6
#
# ===========================================================================
cd ~/Development/ewslms/ldc-lang

echo "   ***************************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-lang:dcpp-0.1.0-gpp6"
echo "   ****"
echo "   ***************************************************"
echo
docker build \
      --build-arg CPP_MULTI="0" \
      --build-arg CPP_VER="6" \
      \
      --build-arg BUILD_EXT_MOD="-gpp6" \
      \
      --build-arg LIB_HOST=http://alpine-nginx-pkgcache \
      --network=pkgnet \
      \
      --file Dockerfile.dcpp \
      -t ewsdocker/ldc-lang:dcpp-0.1.0-gpp6 .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-lang:dcpp-0.1.0-gpp6 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-lang-dcpp-0.1.0-gpp6"
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
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-lang-dcpp-0.1.0-gpp6:/root \
  -v ${HOME}/.config/docker/ldc-lang-dcpp-0.1.0-gpp6/workspace:/workspace \
  \
  --name=ldc-lang-dcpp-0.1.0-gpp6 \
ewsdocker/ldc-lang:dcpp-0.1.0-gpp6
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-lang-dcpp-0.1.0-gpp6 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-lang-dcpp-0.1.0-gpp6 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-lang-dcpp-0.1.0-gpp6
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-lang-dcpp-0.1.0-gpp6 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-lang:dcpp successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

