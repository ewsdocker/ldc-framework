#!/bin/bash
cd ~/Development/ewsldc/ldc-core
chmod +x builds/*.sh
builds/build-dcore.sh

cd ~/Development/ewsldc/ldc-library
chmod +x builds/*.sh
builds/build-dlibrary.sh

cd ~/Development/ewsldc/ldc-core
chmod +x builds/*.sh
builds/build-dbase.sh
builds/build-dgui.sh

cd ~/Development/ewsldc/ldc-lang
chmod +x builds/*.sh
builds/build-dcc-gpp6.sh
builds/build-dcc-gpp6gui.sh

cd ~/Development/ewsldc/ldc-langdev
chmod +x builds/*.sh
builds/build-dcc-gpp6.sh
builds/build-dcc-gpp6gui.sh

cd ~/Development/ewsldc/ldc-lang
chmod +x builds/*.sh
builds/build-dgtk-common.sh
builds/build-dgtk-gtk2.sh
builds/build-dgtk-gtk3.sh
builds/build-dgtk.sh

cd ~/Development/ewsldc/ldc-lang
chmod +x builds/*.sh
builds/build-djava-jre8.sh
builds/build-djava-jre8gtk2.sh
builds/build-djava-jre11.sh
builds/build-djava-jre11gtk3.sh

cd ~/Development/ewsldc/ldc-lang
chmod +x builds/*.sh
builds/build-dphp-php5.6gui.sh

cd ~/Development/ewsldc/ldc-lang
chmod +x builds/*.sh
builds/build-dqt-qt4.sh
builds/build-dqt-qt5.sh
builds/build-dqt-qt.sh

cd ~/Development/ewsldc/ldc-langdev
chmod +x builds/*.sh
builds/build-dgtk-common.sh
builds/build-dgtk-gtk2.sh
builds/build-dgtk-gtk3.sh

cd ~/Development/ewsldc/ldc-langdev
chmod +x builds/*.sh
builds/build-djava-jdk8.sh
builds/build-djava-jdk8gtk2.sh
builds/build-djava-jdk11.sh
builds/build-djava-jdk11gtk3.sh

cd ~/Development/ewsldc/ldc-langdev
chmod +x builds/*.sh
builds/build-dphp-php5.6gui.sh

cd ~/Development/ewsldc/ldc-langdev
chmod +x builds/*.sh
builds/build-dqt.sh
builds/build-dqt-qt4gui.sh
builds/build-dqt-qt5gui.sh
builds/build-dqt-qt5gtk3.sh

cd ~/Development/ewsldc/ldc-langdev
chmod +x builds/*.sh
builds/build-dfortran-gpp6.sh
builds/build-dfortran-jdk8-gtk2.sh


