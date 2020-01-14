#!/bin/bash

# ========================================================================================
#
#		ldc-core:base install gosu
#
# ========================================================================================

echo "***********************"
echo ""
echo "   install_gosu"
echo ""
echo "***********************"

curl  -o /usr/local/bin/gosu -SL "${GSU_URL}"
chmod +x /usr/local/bin/gosu

exit 0
