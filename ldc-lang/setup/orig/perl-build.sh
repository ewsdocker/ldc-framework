#!/bin/bash

echo ""
echo "   *** Making PERL ${PRL_PKG} ****"
echo ""

wget ${PRL_URL}
tar -xzf ${PRL_PKG}
cd ${PRL_NAME}
./Configure -des -Dprefix="${PRL_DIR}"

apt-get -y update
apt-get -y install make

make
make test
make install

