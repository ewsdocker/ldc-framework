#!/bin/bash

apt-get -y update
apt-get -y upgrade
apt-get -y install \
           gfortran \
           gfortran-6 \
           libcoarrays-dev \
           libcoarrays0d \
           libfabric1 \
           libgfortran-6-dev \
           libgfortran3 \
           libhwloc-plugins \
           libhwloc5 \
           libibverbs1 \
           libicu57 \
           libltdl7 \
           libnl-3-200 \
           libnl-route-3-200 \
           libnuma1 \
           libopenmpi2 \
           libpciaccess0 \
           libpsm-infinipath1 \
           librdmacm1 \
           libxml2 \
           mpi-default-bin \
           ocl-icd-libopencl1 \
           openmpi-bin \
           openmpi-common \
           pciutils \
           xml-core 

apt-get clean all
