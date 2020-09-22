#!/bin/bash
# ===========================================================================
#
#    ldc-library:dlibrary-0.1.6-b4
#
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** stopping library container(s)"
echo "   ****"
echo "   ********************************************"
echo
docker stop ldc-library-dlibrary-0.1.0-b4
docker rm ldc-library-dlibrary-0.1.0-b4

echo "   ********************************************"
echo "   ****"
echo "   **** installing ldc-library-dlibrary-0.1.6-b4 library"
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
      -v ${HOME}/.config/docker/ldc-library-dlibrary-0.1.6-b4:/root \
      -v ${HOME}/.config/docker/ldc-library-dlibrary-0.1.6-b4/workspace:/workspace \
      \
      --mount source=pkgcache,target=/repo \
      \
      --name=ldc-library \
ewsdocker/ldc-library:dlibrary-0.1.6-b4 
[[ $? -eq 0 ]] ||
 {
 	echo "ldc-library-dlibrary-0.1.6-b4 failed."
 	exit 2
 }

# ===========================================================================
# ===========================================================================

echo "   ********************************************"
echo "   ****"
echo "   **** ldc-library:dlibrary-0.1.6-b4 successfully installed."
echo "   ****"
echo "   ********************************************"
echo

exit 0
