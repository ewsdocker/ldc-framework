
__Preliminary Documentation__ - 2020-06-11
____  
__ldc-framework: The LDC (Linux Docker Containers) Framework project.__  
____  

<table>
 <thead>
  <tr><th>NOTE</th></tr>
 </thead>
 <tbody>
  <tr><td>The <b>ewsdocker/ldc-framework</b> documentation has moved to the <a href="https://ewsdocker.github.io/ldc-framework/">LDC Framework Web Page</a>.
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
 <a href="#Overview">Overview</a>
 <br />
 <a href="#relinfo">Release Information</a>
 <br />
 <ul>
  <a href="#rellink">Release Links</a>
  <br />
  <a href="#relnote">Release Notes</a>  
 </ul>
</ul>
<hr />
<a name="Overview"><b>Overview</b></a>  

The primary goal of the <b>LDC Framework</b> project is to 
streamline <i>Docker</i> container development by providing:

<ul>
  <li>
    a container structure which lends itself to replication within 
    each created container, including those used as a base 
    for a new container;
  </li>
  <li>
    a directory structure allowing for utility libraries and 
    scripts, a standard, expandable <i>Docker</i>-host interface, 
    and well defined internal structure for consistent behavior 
    of system daemons and developed applications;
  </li>
  <li>
    <a href="http://kaptain.sourceforge.net/"
       style="color:blue;font-style:italic;font-size:16px;">Kaptain</a>, 
    <span style="font-style:italic">Dialog</span>, 
    <span style="font-style:italic">Whiptail</span> and 
    <span style="font-style:italic">Zenity</span> to provide 
      simple dialog boxes;
  </li>
  <li>
    <span style="font-style:italic;">CKaptain</span> pop-up 
    dialog boxes, based on 
    <a href="http://kaptain.sourceforge.net/"
       style="color:blue;font-style:italic;font-size:16px;">Kaptain</a>, to provide 
    <ul>
      <li>
        form-based user interfaces for scripts and console-based applications,
      </li>
      <li>
        easily defined, form-based input of options for console-based 
        applications;
      </li>
    </ul>
  </li>
  <li>
    framework stacks for 
    <ul>
      <li>
        single development language applications (for example: 
        <span style="font-style:italic;">C++ Run-time</span>, or 
        <span style="font-style:italic;">Java 
          Run-time Environment</span>);
      </li>
      <li>
        multiple development language applications, (for example: 
        <span style="font-style:italic;">GTK-3 with C++</span>, or
        <span style="font-style:italic;">Java Run-time Environment
          plus GTK-3</span>); and
      </li>
      <li>
        specialized multi-layered development languages for 
        applications (for example: 
        <span style="font-style:italic;">C++, GTK-3 and Java Runtime
          Environment for LibreOffice</span>).
      </li>
    </ul>
  </li>
</ul>

</div>
<hr />  


<a name="relinfo"><b><u>Release Information</u></b></a>  
<ul>
 <b>ldc-framework</b> is in pre-release as version <i>0.1.0</i> during transition from independent projects to a more coherent, single project forming an easy to maintain framework to support  fast creation of <b>Docker</b>-based container applications.
<br />

<br />
The home page of the <a href="https://github.com/ewsdocker/ldc-framework)">ldc_framework</a> is the current development, which can be considered to be neither safe to use nor fully operational.
<br />

<br />
The newest release version is the <i>current release</i> of the source, and forms the basis for the <i>latest release</i> images in the <b>Docker</b> repository.  
<br />

<br />
All release versions are available in both <i>Zip</i> and <i>Tar-Gzip</i> archival format, available through the <i>release</i> selection on the GIT menu.  
</ul>

<a name="rellink"><b><u>Release Version Links</u></b></a>
<ul>
<table border=1>
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
  <td>&nbsp;2020-06-11&nbsp;</td>
  <td>&nbsp;&nbsp;</td>
  <td>&nbsp;&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;0.1.0-b2&nbsp;</td>
  <td>&nbsp;Released&nbsp;</td>
  <td>&nbsp;2020-06-11&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-framework/archive/ldc-framework-0.1.0-b2.zip">0.1.0-b2.zip</a>&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-framework/archive/ldc-framework-0.1.0-b2.tar.gz">0.1.0-b2-tar-gz</a>&nbsp;</td>
 </tr>
 <tr>
  <td>&nbsp;0.1.0-b1&nbsp;</td>
  <td>&nbsp;Released&nbsp;</td>
  <td>&nbsp;2020-05-30&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-framework/archive/ldc-framework-0.1.0-b1.zip">0.1.0-b1.zip</a>&nbsp;</td>
  <td>&nbsp;<a href="https://github.com/ewsdocker/ldc-framework/archive/ldc-framework-0.1.0-b1.tar.gz">0.1.0-b1-tar-gz</a>&nbsp;</td>
 </tr>
</table>
</ul>

<a name="relnote"><b><u>Release Notes</u></b></a>
<ul>
 <dl>
  <dt>0.1.0-b3</dt>
   <dd>
    <li></li>
   </dd>

  <br />

  <dt>0.1.0-b2</dt>
   <dd>
    <li>Upgraded to Debian 10.4</li>
    <li>Upgraded all projects to work with Debian 10 
    <li>JavaScript updated to NodeJS-14 and is stable.</li>
    <li>GTK-3 and JDK-13 are stable.</li>
    <li>JDK-8 no longer supported, replaced with JDK-11.</li>
    <li>Broken automatic run-time configuration.</li>
    <li>Broken desktop configuration - use docker start from console.</li>
   </dd>

  <br />

  <dt>0.1.0-b1</dt>
   <dd>
    <li>Transition all projects to the framework project.</li>
    <li>Re-write of all framework project blocks.</li>
    <li>Formalized Dockerfile formats and base configuration.</li>
    <li>Uniform approach to all builds and run configuration.</li>
    <li>Upgraded to Debian 9.12</li>
    <li>Broken automatic run-time configuration.</li>
    <li>Broken desktop configuration - using docker start from console.</li>
    <li>JavaScript won't install.</li>
    <li>Problems persist with GTK-3 and JDK-13.</li>
   </dd>
 </dl>
</ul>
<hr />
<b>Copyright © 2017-2020. EarthWalk Software.</b>
<br />
<br />
