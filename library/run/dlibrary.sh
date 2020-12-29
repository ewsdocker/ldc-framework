#!/bin/bash

. ~/Development/ewsldc/ldc/ldc-common.sh

# ===========================================================================
#
#    ldc-library:dlibrary${ldclib}${ldcextv}
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping library container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-library-dlibrary${ldclib}${ldcextv}
docker rm ldc-library-dlibrary${ldclib}${ldcextv}

echo "   ********************************************"
echo "   ****"
echo "   **** installing ldc-library-dlibrary${ldclib}${ldcextv} library"
echo "   ****"
echo "   ********************************************"
echo

docker run \
      -it \
      --rm \
      \
      -e LMS_BASE="${HOME}/.local" \
      \
      -v /etc/localtime:/etc/localtime:ro \
      \
      -v ${HOME}/bin:/userbin \
      -v ${HOME}/.local:/usrlocal \
      -v ${HOME}/.config/docker:/conf \
      -v ${HOME}/.config/docker/ldc-library-dlibrary${ldclib}${ldcextv}:/root \
      -v ${HOME}/.config/docker/ldc-library-dlibrary${ldclib}${ldcextv}/workspace:/workspace \
      \
      --mount source=pkgcache,target=/repo \
      \
      --name=ldc-library \
ewsdocker/ldc-library:dlibrary${ldclib}${ldcextv} 
[[ $? -eq 0 ]] ||
 {
 	echo "ldc-library-dlibrary${ldclib}${ldcextv} failed."
 	exit 2
 }

# ===========================================================================
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-library:dlibrary${ldclib}${ldcextv} successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0
