#!/bin/bash
# ===========================================================================
#
#    ldc-base:dperl-0.1.0-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl container"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-base-dperl-0.1.0-b4 2>null
docker rm ldc-base-dperl-0.1.0-b4 2>null

echo "   ***********************************************"
echo "   ****"
echo "   **** installing ldc-base-dperl-0.1.0-b4 container"
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
  -v ${HOME}/.config/docker/ldc-base-dperl-0.1.0:/root \
  -v ${HOME}/.config/docker/ldc-base-dperl-0.1.0/workspace:/workspace \
  \
  --name=ldc-base-dperl-0.1.0-b4 \
ewsdocker/ldc-base:dperl-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "build container ldc-base-dperl-0.1.0-b4 failed."
 	exit 2
 }

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-base-dperl-0.1.0-b4 daemon"
echo "   ****"
echo "   ********************************************"
echo

docker stop ldc-base-dperl-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "stop ldc-base-dperl-0.1.0-b4 failed."
	exit 3
 }

docker rm ldc-base-dperl-0.1.0-b4
[[ $? -eq 0 ]] ||
 {
 	echo "rm ldc-base-dperl-0.1.0-b4 failed."
	exit 4
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-base:dperl successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0

