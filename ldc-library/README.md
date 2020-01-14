__Preliminary Documentation__ - 2019-10-27  
____  
____  
__ldc-library: The LDC (Linux Docker Container) Library Project.__  

The __LDC Library Project__ provides the libraries, scripts, templates and structures for use with the __LDC Stack Project__.  

Current Development Version: __0.1.6-b1__  
Latest Beta Release: __n/a__  

Latest Stable Release: __[0.1.5](https://github.com/ewsdocker/ldc-library/releases)__  

Docker images: [ewsdocker/ldc-library](https://hub.docker.com/r/ewsdocker/ldc-library)
____  
____  
The __LDC Library Project__ provides the scripts, special purpose maintenance libraries and applications, and the *Docker*-host interface structure of the __LDC Stack__ project, for a standard look and feel across all *Stack Blocks* and derivative __LDC Projects__.  

<b>dlibrary</b> extends the <b>dcore</b> <i>Stack Block</i> to provide utility libraries and scripts, a standard <i>Docker</i>-host interface, and a common internal structure for consistent, well-defined behavior of daemons as well as applications.  
<br>
<br>
The <b>LDC Library Project</b> has been moved to it's own project in order to allow for independent development, and support releases as a plug-in to the downstream <i>Stack Block</i>s.  
____  
____  
The __[LDC Library Wiki](https://github.com/ewsdocker/ldc-library/wiki/)__ (under development) contains the documentaion for the __LDC Library__. 

## Table of Contents

   * [ldc-library](https://github.com/ewsdocker/ldc-library/wiki/)  
      * [About ldc-library](https://github.com/ewsdocker/ldc-library/wiki/Home#about-ldc-library)  
      * [Newest Version](https://github.com/ewsdocker/ldc-library/wiki/Home#newest-version)
      * [ldc-library Web Site](https://ewsdocker.github.io/ldc-library/)  

   * [Quick Start](https://github.com/ewsdocker/ldc-library/wiki/QuickStart)
      * [Generating ldc-library](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#generating-ldc-library)  
         * [Environment Variables](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#environment-variables)  
         * [Data Volumes](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#data-volumes)  
      * [Getting the source](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#getting-the-source)  
         * [As Git repository](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#as-git-repository)  
         * [As tarball or zip archive](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#as-tarball-or-zip-archive)  

      * [Building the ldc-library tarball](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#building-the-ldc-library-tarball) 
         * [Build arguments](https://github.com/ewsdocker/ldc-library/wiki/QuickStart#build-arguments) 


   * [Docker Tags](https://github.com/ewsdocker/ldc-library/wiki/DockerTags)  
      * [Docker Tag History](https://github.com/ewsdocker/ldc-library/wiki/DockerTags#docker-tag-history)
   

   * [License](https://github.com/ewsdocker/ldc-library/wiki/License)



____  
____  
**LDC Library Release Numbering**  
A new release of the __LDC Library__ will cause a new release in the __LDC Stack Project__.  However, a new release of the __LDC Stack Project__ won't necessarily cause a new release of the __LDC Library__.  

For example, an upgrade to the underlying operating system will cause a new release of the __LDC Stack Project__, but if it does not affect the __LDC Library__ components, a new __LDC Library__ will not be released.

____  
____  

The __Current Development Version__, and all __beta__ releases should be considered unusable and useful for testing purposes only.  They are not intended to be used for production at this time.  

<ul>  
  <dl>  
    <dt><u>0.1.6-b1</u></dt>  
    <dd>  
      <dl>  
        <dt>dlibrary</dt>  
          <dd>ldc-library:dlibrary<b>-0.1.6-b1</b></dd><br>  
      </dl>  
    </dd>  
  </dl>  
</ul>  

____  
____  
__Getting Released Versions__  
The current source shown on this page is the __Current Development Version__, and is available through the green "Clone or download" button on this page.   All __Release Version__s are shown by selecting the _releases_ item above the green bar at the top of this page.  

____  
____  
**Installing ewsdocker/ldc-library**  
Pre-built _Docker_ containers are available from [ewsdocker/ldc-library](https://hub.docker.com/r/ewsdocker/ldc-library) on [Docker Hub](https://hub.docker.com).  If a __Latest Release Version__ is not found on the _Docker Hub_ site, then it has not yet been released.

<ul>  
The following scripts will download the selected <b>ewsdocker/ldc-library</b> 
<i>Stack Block</i>, create a container, and setup and populate the directory structures on both the <i>Docker</i>-host and the container.  

The _default_ values will install all directories and contents in the <i>docker host</i> user's home directory (refer to [Mapping docker host resources to the docker container](https://hub.docker.com/r/ewsdocker/debian-base/wiki/QuickStart#mapping"), in the [debian-base wiki](https://hub.docker.com/r/ewsdocker/debian-base/wiki/)).  

 <br>  

 <b>LDC Library Release 0.1.6-b1<b>
 <br><hr>  
 <br>  

 <b>ewsdocker/ldc-library:dlibrary-0.1.6-b1</b>  
 <ul>  

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
      -v ${HOME}/.config/docker/ldc-library-dlibrary-0.1.6-b1:/root \
      \
      --mount source=pkgcache,target=/repo \
      \
      --name=ldc-library \
    ewsdocker/ldc-library:dlibrary-0.1.6-b1  
    
 </ul>  

 <br><hr>  
 <br>  

</ul>  

____  
____  

