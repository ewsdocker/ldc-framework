#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-library:dlibrary${ldclib}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/library

echo "   ********************************************"
echo "   ****"
echo "   **** removing dlibrary images"
echo "   ****"
echo "   ********************************************"
echo
docker rmi ewsdocker/ldc-library:dlibrary${ldclib}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** building ewsdocker/ldc-library:dlibrary${ldclib}${ldcextv} image"
echo "   ****"
echo "   ********************************************"
echo

docker build \
  --build-arg RUN_APP="lms-utilities-install" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-library" \
  --build-arg BUILD_VERSION="dlibrary" \
  --build-arg BUILD_VERS_EXT="${ldclib}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-core" \
  --build-arg FROM_VERS="dcore" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --build-arg ULMSLIB_NAME="ldc-library" \
  --build-arg ULMSLIB_USR_DIR="usr" \
  --build-arg ULMSLIB_ETC_DIR="etc/lms" \
  --build-arg ULMSLIB_DEST="/repo" \
  --build-arg ULMSLIB_VERS="${libver}" \
  --build-arg ULMSLIB_VERSX="${ldcextv}" \
  \
  --file Dockerfile \
-t ewsdocker/ldc-library:dlibrary${ldclib}${ldcextv} .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-library:dlibrary${ldclib}${ldcextv} failed."
 	exit 1
 }

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-library-dlibrary${ldclib}${ldcextv} built."
echo "   ****"
echo "   ********************************************"
echo

. run/dlibrary.sh

