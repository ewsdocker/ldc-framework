

__Preliminary Documentation__ - 2020-09-23
____  
__ldc-applications: The LDC (Linux Docker Containers) Applications project.__  

____  

<table>
 <thead>
  <tr><th>NOTE</th></tr>
 </thead>
 <tbody>
  <tr><td>The <b>ewsdocker/ldc-applications</b> documentation has moved to the <a href="https://ewsdocker.github.io/ldc-applications/">LDC Framework Web Page</a>.
    <br>
    <br />
    The documentation on this page is being maintained only when <i>version</i> changes to
    the project are published. </td>
  </tr>
 </tbody>
</table>  

____  
<b>Contents</b>
<ul>
 <a href="#relinfo">Release Information</a>
 <br>
 <ul>
  <a href="#rellink">Release Links</a>
 </ul>
 <a href="#appslink">Application Links</a>
 <ul>
  <a href="#appsbrowser"><b><u>ldc-browser</u></b></a>
  <ul>
   <a href="#firefox">Firefox</a>
   <br>
   <a href="#firefoxq">Firefox Quantum</a>
   <br>
   <a href="#firefoxe">Firefox-esr</a>
   <br>
   <a href="#nsurf">NetSurf</a>
   <br>
   <a href="#pmoon">Pale Moon</a>
   <br>
   <a href="#wfox">Waterfox Classic</a>
   <br>
   <a href="#wfoxc">Waterfox Current</a>
  </ul>
  <a href="#appsconsole"><b><u>ldc-console</u></b></a>
  <ul>
   <a href="#nano">Nano</a>
   <br>
   <a href="#tumblr">Tumblr</a>
  </ul>
  <a href="#appsdesktop"><b><u>ldc-desktop</u></b></a>
  <ul>
   <a href="#dia">Dia</a>
   <br>
   <a href="#gimp">Gimp</a>
   <br>
   <a href="#mpad">Mousepad</a>
   <br>
   <a href="#ripme">RipMe</a>
  </ul>
  <a href="#appseclipse"><b><u>ldc-eclipse</u></b></a>
  <ul>
   <a href="#cpp">Cpp</a>
   <br>
   <a href="#fortran">Fortran</a>
   <br>
   <a href="#java">Java</a>
   <br>
   <a href="#javascript">JavaScript</a>
   <br>
   <a href="#php">Php</a>
   <br>
   <a href="#qt">Qt</a>
  </ul>
  <a href="#appsgames"><b><u>ldc-games</u></b></a>
  <ul>
   <a href="#aisleriot">Aisleriot Solitaire</a>
   <br>
   <a href="#mahjongg">Mahjongg</a>
  </ul>
  <a href="#appslibre"><b><u>ldc-libre</u></b></a>
  <ul>
   <a href="#office">Office</a>
   <br>
   <a href="#officejdk">Office + JDK-13</a>
  </ul>
 </ul>
 <a href="#relnote">Release Notes</a>  
</ul>

<hr />

<a name="relinfo"><b><u>Release Information</u></b></a>  
<ul>
 <b>ldc-applications</b> is in pre-release as version <i>0.1.0</i> during transition from independent projects to a more coherent, single project to support  fast creation of <b>Docker</b> container applications based on the <a href="https://github.com/ewsdocker/ldc-framework"><b>LDC Framework</b></a> project.
<br />

<br>
The home page of the <a href="https://github.com/ewsdocker/ldc-applications"><b>ldc-applications</b></a> is the current development, which can be considered to be neither safe to use nor fully operational.
<br />

<br>
The newest release version is the <i>current release</i> of the source, and forms the basis for the <i>latest release</i> images in the <b>Docker</b> repository.  
<br />

<br>
All release versions are available in both <i>Zip</i> and <i>Tar-Gzip</i> archival format, available through the <i>release</i> selection on the GIT menu.  
</ul>

<a name="rellink"><b><u>Release Version Links</u></b></a>
<ul>
<table border=0>
 <tr>
  <th>&nbsp;Release&nbsp;</th>
  <th>&nbsp;Status&nbsp;</th>
  <th>&nbsp;Date&nbsp;</th>
  <th>&nbsp;Zip Source&nbsp;</th>
  <th>&nbsp;Tar Source&nbsp;</th>
 </tr>
 <tr>
  <td>&nbsp;0.1.0-b3&nbsp;</td>
  <td>&nbsp;Development&nbsp;</td>
  <td>&nbsp;2020-06-13&nbsp;</td>
  <td>&nbsp;&nbsp;</td>
  <td>&nbsp;&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;0.1.0-b2&nbsp;</td>
  <td>&nbsp;Released&nbsp;</td>
  <td>&nbsp;2020-06-12&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-applications/archive/ldc-applications-0.1.0-b2.zip">0.1.0-b2.zip</a>&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-applications/archive/ldc-applications-0.1.0-b2.tar.gz">0.1.0-b2-tar-gz</a>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;0.1.0-b1&nbsp;</td>
  <td>&nbsp;Released&nbsp;</td>
  <td>&nbsp;2020-05-30&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-applications/archive/ldc-applications-0.1.0-b1.zip">0.1.0-b1.zip</a>&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-applications/archive/ldc-applications-0.1.0-b1.tar.gz">0.1.0-b1-tar-gz</a>&nbsp;</td>
 </tr>
</table>
</ul>

<hr />

<a name="appslink"><b><u>Application Links version 0.1.0-b2</u></b></a>

<dl>
 <dt><a name="appsbrowser"></a><a href="https://hub.docker.com/repository/docker/ewsdocker/ldc-browser">ldc-browser</a></dt>
  <dd>
   <dl>
    <dt><a name="firefox"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#firefox">Firefox 77.0.1</a><br />&nbsp;</dt>
     <dd>"The latest Firefox brings privacy protections front and center, 
          letting you track the trackers." This version is based upon the
          latest <b><i>unstable</i></b> repository for Debian 10.<br />&nbsp;
     </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:firefox-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="firefoxq"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#firefoxq">Firefox Quantum 77.0.1</a><br />&nbsp;</dt>
     <dd>"The latest Firefox brings privacy protections front and center, 
          letting you track the trackers." This version is based upon the
          latest <b><i>stable</i></b> repository for Debian 10.<br />&nbsp;
     </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:ffquantum-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="firefoxe"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#firefoxe">Firefox-esr 68.8.0esr-1</a><br />&nbsp;</dt>
     <dd>"The latest Firefox brings privacy protections front and center, 
          letting you track the trackers." This version is based upon the
          latest <b><i>esr stable</i></b> repository for Debian 10.<br />&nbsp;
     </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:firefox-esr-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="nsurf"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#nsurf">NetSurf 3.10-1</a><br />&nbsp;</dt>
      <dd>"NetSurf is a free, open source web browser. 
          It is written in C and released under the GNU Public Licence, 
          version 2. NetSurf has its own layout and rendering engine and is
          small and capable of handling many web standards." This version is 
          based upon the latest <b><i>testing</i></b> repository for Debian 10.<br />&nbsp;
      </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:netsurf-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="pmoon"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#pmoon">Pale Moon 28.10.0</a><br />&nbsp;</dt>
      <dd>"Pale Moon is an Open Source, Goanna-based web browser 
          built from its own, independently developed source ... and 
          focuses on efficiency and ease of use."<br />&nbsp;
      </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:palemoon-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="wfox"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#wfox">Waterfox Classic 2020-06</a><br />&nbsp;</dt>
      <dd>"Waterfox does not collect tracking or usage information, and
          supports 64-Bit NPAPI plugins (such as FlashPlayer).  Explore 
          the web the way it was meant to be!"<br />&nbsp;
      </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:waterfox-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="wfoxc"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/browser#wfoxc">Waterfox Current 2020-06</a><br />&nbsp;</dt>
      <dd>"Waterfox does not collect tracking or usage information, and
          supports 64-Bit NPAPI plugins (such as FlashPlayer).  Explore 
          the web the way it was meant to be!"<br />&nbsp;
      </dd>  
     <dd><b>docker pull ewsdocker/ldc-browser:waterfox-current-0.1.0-b2</b></dd>
   </dl>
  </dd>
</dl>

<dl>
 <dt><a name="appsconsole"><a href="https://hub.docker.com/repository/docker/ewsdocker/ldc-console">ldc-console</a></dt>
  <dd>
   <dl>
    <dt><a name="nano"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/console#nano">Nano 3.2</a><br />&nbsp;</dt>
     <dd>
      GNU nano is an easy-to-use console-based text editor originally designed as a replacement for Pico, implementing many features missing in Pico.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-console:nano-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="tumblr"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/console#tumblr">Tumblr 0.0.2</a><br />&nbsp;</dt>
     <dd>
      Tumblr is a console-based application to mass download images from <i>tumblr</i>-based galleries.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-console:tumblr-0.1.0-b2</b></dd>
   </dl>
  </dd>
</dl>

<dl>
 <dt><a name="appsdesktop"><a href="https://hub.docker.com/repository/docker/ewsdocker/ldc-desktop">ldc-desktop</a></dt>
  <dd>
   <dl>
    <dt><a name="dia"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/desktop#dia">Dia 0.97.3</a><br />&nbsp;</dt>
     <dd>
      Dia is free and open source general-purpose diagramming software.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-desktop:dia-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="gimp"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/desktop#gimp">Gimp 2.10.8-2</a><br />&nbsp;</dt>
     <dd>
      GIMP (GNU Image Manipulation Program) is a raster graphics editor used for image retouching and editing, free-form drawing, converting between different image formats, and more specialized tasks.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-desktop:gimp-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="mpad"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/desktop#mpad">Mousepad 0.4.1-2</a><br />&nbsp;</dt>
     <dd>
      Mousepad is a graphical text editor for Xfce based on Leafpad, providing printing support, which was missing from Leafpad.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-desktop:mousepad-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="ripme"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/desktop#ripme">RipMe 1.7.92</a><br />&nbsp;</dt>
     <dd>
      RipMe is a Java-based tool with both graphical and command line interfaces, to mass download images from various sources.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-desktop:ripme-0.1.0-b2</b></dd>
   </dl>
  </dd>
</dl>

<dl>
 <dt><a name="appseclipse"><a href="https://hub.docker.com/repository/docker/ewsdocker/ldc-eclipse">ldc-eclipse</a></dt>
  <dd>
   <dl>
    <dt><a name="cpp"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/eclipse#cpp">Eclipse CPP 2020-05-M3</a><br />&nbsp;</dt>
     <dd>
      Eclipse is an integrated development environment (IDE) used in computer programming. This version uses the CPP release and built around the C and C++ run-times and compilers.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-eclipse:cpp-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="fortran"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/eclipse#fortran">Eclipse Fortran 2020-05-M3</a><br />&nbsp;</dt>
     <dd>
      Eclipse is an integrated development environment (IDE) used in computer programming. This version uses the CPP release and the Fortran 6, C, and C++ run-times and compilers.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-eclipse:fortran-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="java"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/eclipse#java">Eclipse Java 2020-05-M3</a><br />&nbsp;</dt>
     <dd>
      Eclipse is an integrated development environment (IDE) used in computer programming. This version uses the Java release and the Java 13 run-times and compilers.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-eclipse:java-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="javascript"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/eclipse#javascript">Eclipse JavaScript 2020-05-M3</a><br />&nbsp;</dt>
     <dd>
      Eclipse is an integrated development environment (IDE) used in computer programming. This version uses the JavaScript release and the JavaScript 13 run-times and compilers.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-eclipse:javascript-0.1.0-b2</b></dd>
    <dt><a name="php"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/eclipse#php">Eclipse Php 2020-05-M3</a><br />&nbsp;</dt>
     <dd>
      Eclipse is an integrated development environment (IDE) used in computer programming. This version uses the PDT release and the Php 5.6 interpreter.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-eclipse:php-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="qt"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/eclipse#qt">Eclipse Qt 2020-05-M3</a><br />&nbsp;</dt>
     <dd>
      Eclipse is an integrated development environment (IDE) used in computer programming.  This version uses the CPP release and the Qt 4 run-times and compilers.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-eclipse:qt-0.1.0-b2</b></dd>
   </dl>
  </dd>
</dl>

<dl>
 <dt><a name="appsgames"><a href="https://hub.docker.com/repository/docker/ewsdocker/ldc-games">ldc-games</a></dt>
  <dd>
   <dl>
    <dt><a name="aisleriot" a href="https://github.com/ewsdocker/ldc-applications/wiki/games#aisleriot">Aisleriot 3.22.7-2</a><br />&nbsp;</dt>
     <dd>
      GNOME solitaire card game collection. This is a collection of over eighty different solitaire card games, including popular variants such as spider, freecell, klondike, thirteen (pyramid), yukon, canfield and many more.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-games:sol-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="mahjongg" a href="https://github.com/ewsdocker/ldc-applications/wiki/games#mahjongg">Mahjongg 3.22.0-4</a><br />&nbsp;</dt>
     <dd>
      This is a solitaire (one player) version of the classic Eastern tile game, Mahjongg.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-games:mahjongg-0.1.0-b2</b></dd>
   </dl>
  </dd>
</dl>
 
<dl>
 <dt><a name="appslibre" href="https://hub.docker.com/repository/docker/ewsdocker/ldc-libre">ldc-libre</a></dt>
  <dd>
   <dl>
    <dt><a name="office"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/libre#office">LibreOffice_6.4.4</a><br />&nbsp;</dt>
     <dd>
      LibreOffice is a powerful and free office suite, a successor to OpenOffice. Its clean interface and feature-rich tools help you unleash your creativity and enhance your productivity.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-libre:office-0.1.0-b2</b><br />&nbsp;</dd>
    <dt><a name="officejdk"></a><a href="https://github.com/ewsdocker/ldc-applications/wiki/libre#office-jdk">LibreOffice_6.4.4 + JDK 13</a><br />&nbsp;</dt>
     <dd>
      This version of LibreOffice is build with the Java programming language, for those plug-ins requiring a Java base.<br />&nbsp;
     </dd>
     <dd><b>docker pull ewsdocker/ldc-libre:office-jdk-0.1.0-b2</b><br />&nbsp;</dd>
   </dl>
  </dd>
</dl>
 
<hr />

<a name="relnote"><b><u>Release Notes</u></b></a>
<ul>
 <dl>
  <dt>0.1.0-b3</dt>
   <dd>
    <li>
     To Do:
     <ul>
      <li>
       Re-factor all applications to reduce the size of the final Docker image;
      </li>
      <li>
       Restore automatic desktop environment generation;
      </li>
      <li>
       Create Wiki and Readme documentation for each application;
      </li>
      <li>
       Add wiki documentation for each application to the application web page;
      </li>
      <li>
       x
      </li>
     </ul>
    </li>
    <li>
    </li>
   </dd>

  <dt>0.1.0-b2</dt>
   <dd>
    <li>
     Upgraded to LDC Framework version <i>0.1.0-b2</i> and <i>Debian 10.4</i>; 
    </li>
    <li>
     Upgraded all application projects to work with <i>Debian 10</i>;
    </li>
    <li>
     Upgraded all applications to current stable release, where available;
    </li>
    <li>
     Upgraded ldc-browser:netsurf to version 3.10 using unstable release;
    </li>
    <li>ldc-browser:firefox reverts to unstable release when a new release is
        available but not in the Debian stable repository,
    </li>
    <li>
     ldc-browser:ffquantum remains the current Debian stable repository;
    </li>
    <li>
     introduced ldc-browser:firefox-esr based upon latest Debian stable repository;
   </dd>

  <dt>0.1.0-b1</dt>
   <dd>
    <li>Transition all projects to the applications project;</li>
    <li>Re-write of all applications projects to use ldc-framework paradigms;</li>
    <li>Uniform approach to all builds and run configuration;</li>
    <li>Upgraded all applications to Debian 9.12;</li>
    <li>ldc-browser:netsurf version 3.8 is broken;</li>
    <li>
     ldc-eclipse:javascript is broken until <i>NodeJS</i> problems are repaired;
    </li>
   </dd>
 </dl>
</ul>
<hr />
<b>Copyright © 2017-2020. EarthWalk Software.</b>
<br />
<br />
