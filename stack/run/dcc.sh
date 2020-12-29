#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-stack:dcc${ldcvers}${ldcextv}
#
# ===========================================================================
echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack:dcc container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dcc${ldcvers}${ldcextv}
docker rm ldc-stack-dcc${ldcvers}${ldcextv}

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-dcc${ldcvers}${ldcextv}"
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
  -v ${HOME}/bin:/userbin \
  -v ${HOME}/.local:/usrlocal \
  -v ${HOME}/.config/docker:/conf \
  -v ${HOME}/.config/docker/ldc-stack-dcc${ldcvers}:/root \
  -v ${HOME}/.config/docker/ldc-stack-dcc${ldcvers}/workspace:/workspace \
  \
  --name=ldc-stack-dcc${ldcvers}${ldcextv} \
ewsdocker/ldc-stack:dcc${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-dcc${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dcc${ldcvers}${ldcextv} daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-dcc${ldcvers}${ldcextv}
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-dcc${ldcvers}${ldcextv} failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-stack:dcc successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

