
__Preliminary Documentation__ - 2020-04-12
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
    The documentation on this page is being maintained only when upgrades to
    the project are published. </td>
  </tr>
 </tbody>
</table>  

____  
### Overview  


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

