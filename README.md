
__Preliminary Documentation__ - 2020-04-12
____  
__ldc-framework: The LDC (Linux Docker Containers) Framework project.__  

____  
### Overview  


<b>core</b>
<ul>
 The <b>LDC Core</b> project is the foundational member of <a href="https:// github.com/ewsdocker/ldc-framework.wiki">LDC Framework</a>, providing the core frame required to construct additional frame's and applications.
<br><br>
 The <b>core</b> project provides the following sub-project(s):
 <dl>
  <dt><u>ldc-core:dcore</u></dt>
  <dd>
   <br>As the first frame in the framework, <b>ldc-core:dcore</b> provides the
   latest <i>Docker</i> operating system container, a system supervisor, system
   services, and frequently needed, console-based applications to form an extensible
   <i>LDC Frame</i>.
  </dd>
 </dl>
</ul>

<hr>

<b>library</b>
<ul>
The <b>LDC Library</b> project provides the structure and support libraries for the framework.
 <br><br>
 The <b>library</b> project provides the following sub-project(s):
 <dl>
  <dt><u>ldc-library:dlibrary</u></dt>
  <dd>
<br>The <b>LDC Library</b> project extends <b>ldc-core:dcore</b> to provide utility libraries and scripts, a standard <i>Docker</i>-host interface, and a common internal structure for consistent, well-defined behavior of daemons as well as applications.
  </dd>
 </dl>
</ul>

<hr>

<b>base</b>
<ul>
The <b>LDC Base</b> project provides the base <i>frame</i>s required to construct <i>LDC Application</i>s and <i>Frame</i>s.
 <br><br>
 The <b>library</b> project provides the following sub-project(s):
 <dl>
  <dt><u>ldc-base:dbase</u></dt>
  <dd>
   <br><b>ldc-base:dbase</b> extends the <b>ldc-core:dcore</b> frame by incorporating 
   the library utility scripts and structures forming the second, or "base", block 
   of the <i>LDC Framework</i> foundation.
   <br><br>
   This frame adds the necessary operating system foundations to provide basic support
   for higher levels of the framework.
  </dd>
 </dl>
 
 <dl>
  <dt><u>ldc-base:dperl</u></dt>
  <dd>
   <br><b>ldc-base:dperl</b> extends the <b>ldc-base:dbase</b> by adding <b>Perl</b>
   support, utility scripts and applications to provide enhanced support for 
   console-based (mult-user) and gui-based (single user) levels of the framework.
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-base:dx11-base</u></dt>
  <dd>
   <br><b>ldc-base:dx11-base</b> extends the <b>ldc-base:dperl</b> to provide audio
   and some multimedia functionality for console-based (mult-user) and gui-based
   (single user) levels of the framework.
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-base:dx11-surface</u></dt>
  <dd>
   <br><b>ldc-base:dx11-surface</b> extends the <b>ldc-base:dx11-base</b> to provide
   robust multimedia functionality, including a <i>Wayland</i> drawing surface, 
   <i>Mesa</i> (OpenGL) and <i>X11</i> server/client library extensions for 
   gui-based applications.
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-base:dx11-dev</u></dt>
  <dd>
   <br><b>ldc-base:dx11-dev</b> extends the <b>ldc-base:dx11-surface</b> to provide
   libraries for development of gui-based applications.
  </dd>
 </dl>
</ul>

<hr>

<b>stack</b>
<ul>
The <b>LDC Stack</b> project provides 
 <br><br>
 The <b>stack</b> project provides the following sub-project(s):
 <dl>
  <dt><u>ldc-stack:dcc</u></dt>
  <dd>
   <br><b>ldc-stack:dcc</b> extends the <b>ldc-base:dx11-base</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dcc-x11</u></dt>
  <dd>
   <br><b>ldc-stack:dcc-x11</b> extends the <b>ldc-base:dx11-surface</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dgtk2</u></dt>
  <dd>
   <br><b>ldc-stack:dgtk2</b> extends the <b>ldc-base:dx11-base</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dgtk2-x11</u></dt>
  <dd>
   <br><b>ldc-stack:dgtk2-x11</b> extends the <b>ldc-base:dcc-x11</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dgtk3</u></dt>
  <dd>
   <br><b>ldc-stack:dgtk3</b> extends the <b>ldc-base:dx11-base</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dgtk3-x11</u></dt>
  <dd>
   <br><b>ldc-stack:dgtk3-x11</b> extends the <b>ldc-base:dcc-x11</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:djre8-x11</u></dt>
  <dd>
   <br><b>ldc-stack:djre8-x11</b> extends the <b>ldc-base:dx11-surface</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dqt4</u></dt>
  <dd>
   <br><b>ldc-stack:dqt4</b> extends the <b>ldc-base:dcc-x11</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack:dqt4-x11</u></dt>
  <dd>
   <br><b>ldc-stack:dqt4-x11</b> extends the <b>ldc-base:dx11-surface</b> to provide
  </dd>
 </dl>
</ul>

<hr>

<b><u>stack-dev</u></b>
<ul>
The <b>LDC Stack-Dev</b> project provides 
 <br><br>
 The <b>stack-dev</b> project provides the following sub-project(s):

 <dl>
  <dt><u>ldc-stack-dev:dcc-dev</u></dt>
  <dd>
   <br><b>ldc-stack-dev:dcc-dev</b> extends the <b>ldc-base:dx11-dev</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-dev:dgtk3-dev</u></dt>
  <dd>
   <br><b>ldc-stack-dev:dgtk3-dev</b> extends the <b>ldc-stack-dev:dcc-dev</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-dev:dqt4-dev</u></dt>
  <dd>
   <br><b>ldc-stack-dev:dqt4-dev</b> extends the <b>ldc-stack-dev:dcc-dev</b> to provide
  </dd>
 </dl>

</ul>

<hr>

<b>stack-apps</b>
<ul>
The <b>LDC Stack-Apps</b> project provides 
 <br><br>
 The <b>stack-apps</b> project provides the following sub-project(s):

 <dl>
  <dt><u>ldc-stack-apps:dcpp-jdk13</u></dt>
  <dd>
   <br><b>ldc-stack-apps:dcpp-jdk13</b> extends the <b>ldc-stack-apps:djdk13-gtk3</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:dfortran-gtk2</u></dt>
  <dd>
   <br><b>ldc-stack-apps:dfortran-gtk2</b> extends the <b>ldc-stack-apps:djre8-gtk2</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:dfortran-jdk13</u></dt>
  <dd>
   <br><b>ldc-stack-apps:dfortran-jdk13</b> extends the <b>ldc-stack-apps:djdk13-gtk3</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:djdk8-gtk2</u></dt>
  <dd>
   <br><b>ldc-stack-apps:djdk8-gtk2</b> extends the <b>ldc-stack:dgtk2</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:djdk13-gtk3</u></dt>
  <dd>
   <br><b>ldc-stack-apps:djdk13-gtk3</b> extends the <b>ldc-stack:dgtk3</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:djdk13dev-gtk3</u></dt>
  <dd>
   <br><b>ldc-stack-apps:djdk13dev-gtk3</b> extends the <b>ldc-stack-dev:dgtk3-dev</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:djre8-gtk2</u></dt>
  <dd>
   <br><b>ldc-stack-apps:djre8-gtk2</b> extends the <b>ldc-stack:dgtk2</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:djs13-jdk13</u></dt>
  <dd>
   <br><b>ldc-stack-apps:djs13-jdk13</b> extends the <b>ldc-stack-apps:djdk13-gtk3</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:dphp5.6-jdk13</u></dt>
  <dd>
   <br><b>ldc-stack-apps:dphp5.6-jdk13</b> extends the <b>ldc-stack-apps:djdk13-gtk3</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-stack-apps:dqt4-jdk13dev</u></dt>
  <dd>
   <br><b>ldc-stack-apps:dqt4-jdk13dev</b> extends the <b>ldc-stack-apps:djdk13dev-gtk3</b> to provide
  </dd>
 </dl>

</ul>

<hr>

<b>dialog</b>
<ul>
The <b>LDC Dialog</b> project provides 
 <br><br>
 The <b>dialog</b> project provides the following sub-project(s):

 <dl>
  <dt><u>ldc-dialog:dialog</u></dt>
  <dd>
   <br><b>ldc-dialog:dialog</b> extends <b>ldc-base:dbase</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-dialog:kaptain</u></dt>
  <dd>
   <br><b>ldc-dialog:kaptain</b> extends <b>ldc-stack:dqt4-x11</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-dialog:whiptail</u></dt>
  <dd>
   <br><b>ldc-dialog:whiptail</b> extends <b>ldc-base:dbase</b> to provide
  </dd>
 </dl>

 <dl>
  <dt><u>ldc-dialog:zenity</u></dt>
  <dd>
   <br><b>ldc-dialog:zenity</b> extends <b>ldc-stack:dgtk3-x11</b> to provide
  </dd>
 </dl>

</ul>

</ul>

<hr>

<b>ck-library</b>
<ul>

 <dl>
  <dt><u>ldc-ck-library:ck-library</u></dt>
  <dd>
The <b>LDC CK-Library</b> project provides <b>CKaptain</b> utility libraries, widgets, pop-up dialogs and scripts to provide consistent, well-defined behavior of daemons as well as applications.
  </dd>
 </dl>
</ul>

<hr>

<b>ckaptain</b>
<ul>
The <b>LDC CKaptain</b> project provides 
 <br><br>
 The <b>ckaptain</b> project provides the following sub-project(s):

 <dl>
  <dt><u>ldc-ckaptain:ckaptain</u></dt>
  <dd>
   <br><b>ldc-ckaptain:ckaptain</b> extends <b>ldc-dialog:kaptain</b> to provide
  </dd>
 </dl>

</ul>

</ul>

____  
__version 0.1.0-b1__  

<ul>  
features consolidation of the framework projects under a single framework project.  
</ul>  

Sketchy documentation is available in the __b1__ release.  However, the original documentation is still available in the original framework project... this will be incorporated into the __b2__ release.  

____  

<table>
 <thead>
  <tr><th>NOTE</th></tr>
 </thead>
 <tbody>
  <tr><td>The <b>ewsdocker/ldc-framework/ldc-core</b> documentation has moved to the <a href="https://github.com/ewsdocker/ldc-framework/wiki/ldc-core">LDC Framework Wiki Core Project</a>.  The documentation on this page is no longer being maintained. </td>
  </tr>
 </tbody>
</table>  

____  
