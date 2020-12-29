#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-ck-library:ck-library${ldcvers}${ldcextv}
#
# ===========================================================================
cd ~/Development/ewsldc/ldc-framework/ck-library

echo "   ********************************************"
echo "   ****"
echo "   **** stopping ldc-ck-library:ck-library container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-ck-library-ck-library${ldcvers}${ldcextv}
docker rm ldc-ck-library-ck-library${ldcvers}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** removing ldc-ck-library-ck-library image(s)"
echo "   ****"
echo "   ********************************************"
echo

docker rmi ewsdocker/ldc-ck-library:ck-library${ldcvers}${ldcextv}

echo "   ***************************************************"
echo "   ****"
echo "   **** building ldc-ck-library:ck-library${ldcvers}${ldcextv}"
echo "   ****"
echo "   ***************************************************"
echo

docker build \
  --build-arg DNAME="CK-LIB" \
  --build-arg QT_VER="QT4" \
  \
  --build-arg RUN_APP="ck-archive" \
  \
  --build-arg KAPTAIN_HOST="${pkgserver}" \
  --build-arg KAPTAIN_VERS="0.8" \
  \
  --build-arg BUILD_PKG="Kaptain v. 0.8" \
  \
  --build-arg BUILD_DAEMON="0" \
  --build-arg BUILD_DESKTOP="CK-Lib" \
  --build-arg BUILD_TEMPLATE="run" \
  \
  --build-arg BUILD_NAME="ldc-ck-library" \
  --build-arg BUILD_VERSION="ck-library" \
  --build-arg BUILD_VERS_EXT="${ldcvers}" \
  --build-arg BUILD_EXT_MOD="${ldcextv}" \
  \
  --build-arg FROM_REPO="ewsdocker" \
  --build-arg FROM_PARENT="ldc-stack" \
  --build-arg FROM_VERS="dqt4-x11" \
  --build-arg FROM_EXT="${ldcvers}" \
  --build-arg FROM_EXT_MOD="${ldcextv}" \
  \
  --build-arg CKLIB_HOST="${pkgserver}" \
  --build-arg CKLIB_DIR="/ckaptain-lib" \
  --build-arg CKLIB_VERS="${ldcbasev}" \
  --build-arg CKLIB_VERSX="${ldcextv}" \
  --build-arg CKLIB_RELEASE="v.0" \
  --build-arg CKLIB_NAME="ckaptain-lib" \
  --build-arg CKLIB_DEST="/repo" \
  \
  --network=pkgnet \
  \
  --file Dockerfile \
-t ewsdocker/ldc-ck-library:ck-library${ldcvers}${ldcextv}  .
[[ $? -eq 0 ]] ||
 {
 	echo "build ewsdocker/ldc-ck-library:ck-library${ldcvers}${ldcextv} failed."
 	exit 1
 }

echo "   ***************************************************************"
echo "   ****"
echo "   **** ldc-ck-library-ck-library${ldcvers}${ldcextv} successfully installed"
echo "   ****"
echo "   ***************************************************************"
echo

. run/ck-library.sh

