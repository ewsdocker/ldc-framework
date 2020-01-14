#!/bin/bash
cd ~/Development/ewsldc/ldc-core
builds/build-dcore.sh

cd ../ldc-library
builds/build-dlibrary.sh

cd ../ldc-core
builds/build-dbase.sh
builds/build-dgui.sh

cd ../ldc-lang
builds/build-dcc-gpp6.sh
builds/build-dcc-gpp6gui.sh

cd ../ldc-langdev
builds/build-dcc-gpp6.sh
builds/build-dcc-gpp6gui.sh

cd ../ldc-lang
builds/build-dgtk-common.sh
builds/build-dgtk-gtk2.sh
builds/build-dgtk-gtk3.sh
builds/build-dgtk.sh

cd ../ldc-lang
builds/build-djava8.sh
builds/build-djava8gtk2.sh
builds/build-djava11.sh
builds/build-djava11gtk3.sh

cd ../ldc-lang
builds/build-dphp-php5.6gui.sh

cd ../ldc-lang
builds/build-dqt-qt4.sh
builds/build-dqt-qt5.sh
builds/build-dqt-qt.sh

cd ../ldc-langdev
builds/build-dgtk-common.sh
builds/build-dgtk-gtk2.sh
builds/build-dgtk-gtk3.sh

cd ../ldc-langdev
builds/build-djava-jdk8.sh
builds/build-djava-jdk8gtk2.sh
builds/build-djava-jdk11.sh
builds/build-djava-jdk11gtk3.sh

cd ../ldc-langdev
builds/build-dphp-php5.6gui.sh

cd ../ldc-langdev
builds/build-dqt.sh
builds/build-dqt4gui.sh
builds/build-dqt5gui.sh
builds/build-dqt5gtk3.sh

cd ../ldc-langdev
builds/build-dfortran-gpp6.sh
builds/build-dfortran-jdk8-gtk2.sh


