#!/bin/bash

# ===========================================================================
#
#    ldc-core:dcore-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcore container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-core-dcore-0.1.0-b4 2>null
docker rm ldc-core-dcore-0.1.0-b4 2>null

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-core-dcore-0.1.0-b4 container"
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
  -v ${HOME}/.config/docker/ldc-core-dcore-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-core-dcore-0.1.0/workspace:/workspace \
  \
  --name=ldc-core-dcore-0.1.0-b4 \
ewsdocker/ldc-core:dcore-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-core-dcore-0.1.0-b4 failed."
 	exit 2
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-core-dcore-0.1.0-b4 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-core-dcore-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-core-dcore-0.1.0-b4 failed."
	exit 3
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-core:dcore successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0
