#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-base:dbase${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base:dbase container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dbase${ldcvers}${ldcextv} 2>null
docker rm ldc-base-dbase${ldcvers}${ldcextv} 2>null

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dbase${ldcvers}${ldcextv} container"
echo "   ****"
echo "   ***********************************************"
echo

docker run \
  -d \
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
  -v ${HOME}/.config/docker/ldc-base-dbase${ldcvers}:/root \
  -v ${HOME}/.config/docker/ldc-base-dbase${ldcvers}/workspace:/workspace \
  \
  --name=ldc-base-dbase${ldcvers}${ldcextv} \
ewsdocker/ldc-base:dbase${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-base-dbase${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dbase${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dbase${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dbase${ldcvers}${ldcextv} failed."
	exit 3
 }

docker rm ldc-base-dbase${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dbase${ldcvers}${ldcextv} failed."
	exit 4
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dbase successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

