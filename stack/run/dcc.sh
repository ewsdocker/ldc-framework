#!/bin/bash
# ===========================================================================
#
#    ldc-stack:dcc-0.1.0-b4
#
# ===========================================================================
echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-stack:dcc container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-stack-dcc-0.1.0-b4
docker rm ldc-stack-dcc-0.1.0-b4

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-stack-dcc-0.1.0-b4"
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
  -v ${HOME}/.config/docker/ldc-stack-dcc-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-stack-dcc-0.1.0/workspace:/workspace \
  \
  --name=ldc-stack-dcc-0.1.0-b4 \
ewsdocker/ldc-stack:dcc-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-stack-dcc-0.1.0-b4 failed."
 	exit 1
 }

echo "   ***********************************************"
echo "   ****"
echo "   **** stopping ldc-stack-dcc-0.1.0-b4 daemon"
echo "   ****"
echo "   ***********************************************"
echo

docker stop ldc-stack-dcc-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-stack-dcc-0.1.0-b4 failed."
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-stack:dcc successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

