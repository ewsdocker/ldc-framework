#!/bin/bash
cd ~/Development/ewslms/ldc-core

echo "   ********************************************"
echo "   ****"
echo "   **** stopping dgui and dcore containers"
echo "   ****"
echo "   ********************************************"
echo
docker rm ldc-core-dcore-0.1.0
docker rm ldc-core-dgui-0.1.0

echo "   ********************************************"
echo "   ****"
echo "   **** removing dgui and dcore images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-core:dgui-0.1.0
docker rmi ewsdocker/ldc-core:dcore-0.1.0

# ===========================================================================
#
#    ldc-core:dcore-0.1.0
#
# ===========================================================================

echo "   **********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-core:dcore-0.1.0"
echo "   ****"
echo "   **********************************************"
echo
builds/build-dcore.sh
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-core:dcore-0.1.0 failed."
 	exit 1
 }

# ===========================================================================
#
#    ldc-core:dgui-0.1.0
#
# ===========================================================================
cd ~/Development/ewslms/ldc-core

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-core:dgui-0.1.0"
echo "   ****"
echo "   ********************************************"
echo
builds/build-dgui.sh
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-core:dgui-0.1.0 failed."
 	exit 2
 }

# ===========================================================================
# ===========================================================================

echo "   **********************************************"
echo "   **********************************************"
echo "   ****"
echo "   **** ldc-core modules successfully installed."
echo "   ****"
echo "   **********************************************"
echo "   **********************************************"
echo

clear
docker images

exit 0

