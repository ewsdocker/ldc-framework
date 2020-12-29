#!/bin/bash
# ===========================================================================
#
#    ldc-base:dperl${ldcvers}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dperl${ldcvers}${ldcextv} 2>null
docker rm ldc-base-dperl${ldcvers}${ldcextv} 2>null

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dperl${ldcvers}${ldcextv} container"
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
  -v ${HOME}/.config/docker/ldc-base-dperl${ldcvers}:/root \
  -v ${HOME}/.config/docker/ldc-base-dperl${ldcvers}/workspace:/workspace \
  \
  --name=ldc-base-dperl${ldcvers}${ldcextv} \
ewsdocker/ldc-base:dperl${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-base-dperl${ldcvers}${ldcextv} failed."
 	exit 2
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dperl${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dperl${ldcvers}${ldcextv} failed."
	exit 3
 }

docker rm ldc-base-dperl${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dperl${ldcvers}${ldcextv} failed."
	exit 4
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dperl successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

