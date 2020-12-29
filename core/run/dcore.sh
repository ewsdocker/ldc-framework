#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-core:dcore${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dcore container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-core-dcore${ldcvers}${ldcextv} 2>null
docker rm ldc-core-dcore${ldcvers}${ldcextv} 2>null

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-core-dcore${ldcvers}${ldcextv} container"
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
  -v ${HOME}/.config/docker/ldc-core-dcore${ldcvers}:/root \
  -v ${HOME}/.config/docker/ldc-core-dcore${ldcvers}/workspace:/workspace \
  \
  --name=ldc-core-dcore${ldcvers}${ldcextv} \
ewsdocker/ldc-core:dcore${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-core-dcore${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-core-dcore${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-core-dcore${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-core-dcore${ldcvers}${ldcextv} failed."
	exit 3
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-core:dcore successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0
