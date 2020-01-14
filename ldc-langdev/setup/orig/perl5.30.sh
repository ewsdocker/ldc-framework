#!/bin/bash

echo ""
echo "   *** Making PERL ${PRL_PKG} ****"
echo ""

wget ${PRL_URL}
tar -xzf ${PRL_PKG}
cd ${PRL_NAME}
./Configure -des -Dprefix="${PRL_DIR}"
make
make test
make install

