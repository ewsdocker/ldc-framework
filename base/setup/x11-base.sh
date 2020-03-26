#!/bin/bash

declare instList=" "

# =========================================================================
#
#	addPkg
#		add specified package to install list
#
#   Enter:
#		instPkg = "package" name to add to the installation list
#		instComment = comment... not used, but tolerated for documentation
#
# =========================================================================
function addPkg()
{
	local instPkg="${1}"
	local instComment="${2}"

    printf -v instList "%s %s" "${instList}" "${instPkg}"
    return 0
}

# =========================================================================
#
#	installList
#		the instList has been build, now execute it
#
#   Enter:
#		none
#
# =========================================================================
function installList()
{
	echo ""
	echo "$instList"
	echo ""

	$instList
	[[ $? -eq 0 ]] || return $?

    return 0
}

# =========================================================================

echo "***********************"
echo ""
echo "   ldc-base:dx11-base"
echo ""
echo "***********************"

apt-get -y update 

addPkg "apt-get -y install"

#
# X-11 
#
addPkg "libice6"

addPkg "libpixman-1-0"

addPkg "libsm6"

addPkg "libx11-6"
addPkg "libx11-data"
addPkg "libx11-xcb1"

addPkg "libxau6"

addPkg "libxdmcp6"
addPkg "libxext6"
addPkg "libxfixes3"
addPkg "libxi6"

addPkg "libxrender1"

addPkg "libxt6" "X11 toolkit intrinsics library"
addPkg "libxtst6"
addPkg "libxvidcore4"

addPkg "x11-common"

#
# dbus
#
addPkg "dbus-x11"

#
# Fonts
#
addPkg "fontconfig-config"
addPkg "fonts-dejavu-core"

addPkg "libfontconfig1"
addPkg "libfreetype6"

#
# X C Binding (xcb)
#
addPkg "libxcb-dri2-0"
addPkg "libxcb-dri3-0"

addPkg "libxcb-glx0"

addPkg "libxcb-icccm4"
addPkg "libxcb-image0"
addPkg "libxcb-keysyms1"

addPkg "libxcb-present0"

addPkg "libxcb-randr0"

addPkg "libxcb-render-util0"
addPkg "libxcb-render0"

addPkg "libxcb-shape0"
addPkg "libxcb-shm0"
addPkg "libxcb-sync1"

addPkg "libxcb-util0"

addPkg "libxcb-xfixes0"

addPkg "libxcb-xinerama0"
addPkg "libxcb-xkb1"

addPkg "libxcb1"

addPkg "libxkbcommon-x11-0"
addPkg "libxkbcommon0"

addPkg "xkb-data"


#
#	Text Processing
#
addPkg "libcairo2"

addPkg "libnewt0.52"

addPkg "whiptail"

#
#	Audio
#
addPkg "alsa-utils" "Network Audio System - shared libraries"

addPkg "libasound2"
addPkg "libasound2-data"
addPkg "libasound2-plugins"

addPkg "libaudio2"

addPkg "libjack-jackd2-0"

addPkg "libpulse-mainloop-glib0" "PulseAudio client libraries (glib2 support)"
addPkg "libpulse0"
addPkg "libpulsedsp"

addPkg "libsndfile1"
addPkg "libsndio6.1"

addPkg "libwebrtc-audio-processing1"

addPkg "pulseaudio"
addPkg "pulseaudio-utils"

addPkg "sndio-tools"
addPkg "sndiod"

#
# Audio encoders/decoders (codec)
#
addPkg "libflac8"

addPkg "libmp3lame0"

addPkg "libshine3"

addPkg "libtwolame0"

addPkg "libvo-amrwbenc0"
addPkg "libvorbis0a"
addPkg "libvorbisenc2"

addPkg "libwavpack1"

#
# Audio signal processing
#
addPkg "libavresample3"
addPkg "libavutil55"

addPkg "libfftw3-single3"

addPkg "libsamplerate0" 
addPkg "libsoxr0"

addPkg "libswresample2"

#
# Speech
#
addPkg "libgsm1"

addPkg "libopencore-amrnb0"
addPkg "libopencore-amrwb0"

addPkg "libopus0"

addPkg "libspeex1"
addPkg "libspeexdsp1"

# =======================================================================

#
# video accelertors and processors
#
addPkg "libva-drm1"
addPkg "libva-x11-1"

addPkg "libva1"
addPkg "libvdpau1"

#
# video encoders/decoders (codec)
#
addPkg "libavcodec57"

addPkg "libcrystalhd3"

addPkg "libogg0"

addPkg "libtheora0"

addPkg "libvpx4"

addPkg "libx264-148"
addPkg "libx265-95"

#
# Video signal processing
#
addPkg "libasyncns0"

addPkg "libltdl7"

addPkg "liborc-0.4-0"

addPkg "libslang2"
addPkg "libsnappy1v5"

addPkg "libtdb1"

addPkg "libzvbi0"
addPkg "libzvbi-common"

addPkg "ucf"

# =======================================================================

#
# Image processing
#
addPkg "libjbig0"
addPkg "libjpeg62-turbo"

addPkg "libopenjp2-7"

addPkg "libpng16-16"

addPkg "libtiff5"

addPkg "libwebp6"
addPkg "libwebpmux2"

#
# Buffer processing
#
addPkg "gir1.2-gdkpixbuf-2.0"

addPkg "libgdk-pixbuf2.0-0"
addPkg "libgdk-pixbuf2.0-common"

#
#  Userspace interface to kernel DRM services -- runtime
#
addPkg "libdrm2"

#
# pgp
#
addPkg "gnupg-agent"

installList
apt-get clean all 

# =======================================================================

exit 0
