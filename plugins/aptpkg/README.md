
__Preliminary Documentation__ - 2020-08-23
____  
__plugins/aptpkg: The LDC (Linux Docker Container) Framework Plugins project.__  

<dl>
<dt>aptpkg - Install the requested packages.</dt>
 <dd>
 <b>apt-get</b> package <b>installer</b>. 
 The following functions are provided to initialize the pkgList buffer,
 add a package name to the pkgList buffer, and execute the pkgList buffer.

 <ul>
 <dl>
  <dt>pkgInit</dt>
   <dd>
   Initializes the pkgList buffer to "apt-get -y install".
   </dd>
  <dt>pkgAddItem ${itemPkg} ${itemComment}</dt>
   <dd>
   Adds the specified package to the installation list.
   </dd>
  <dt>pkgInstall</dt>
   <dd>
   Execute the installation list.
  </dd>
 </dl>
</ul>

Installation:
<ul>
 <li>
  The <i>aptpkg</i> plugin has no requirements other than a <i>bash</i> interpreter.
 </li>
 <li>
  Install all plugin scripts before local script code;
 </li>
</ul>
</dd>

</dl>
