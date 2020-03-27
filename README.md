
__Preliminary Documentation__ - 2020-03-27
____  
__ldc-framework: The LDC (Linux Docker Containers) Framework project.__  

____  
###Overview  

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
