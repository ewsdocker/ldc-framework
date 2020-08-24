
__Preliminary Documentation__ - 2020-08-24
____  
__plugins/aptpkg: The LDC (Linux Docker Container) Framework Plugins project.__  

<dl>
<dt>aptpkg - Install the requested packages.</dt>
 <dd>
 <b>apt-get</b> package <b>installer</b>. 
 

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

<hr \>

<dt>nodejs - Install the specified NodeJS packages.</dt>
 <dd>
 Install <b>NodeJS</b> APT repository and packages. 
 

 <ul>
 <dl>
  <dt>njsInstallArchive ${NJS_URL} ${NJS_NAME}</dt>
   <dd>
   Install the NodeJS repository into the APT index.
   </dd>
  <dt>njsInstallJs</dt>
   <dd>
   The archive is already install in APT, so now
   install the nodejs and specified packages.
   </dd>
  <dt>njsInstall ${NJS_URL} ${NJS_NAME}</dt>
   <dd>
   Install the specified NJS_URL/NJS_NAME NodeJS version
   into the APT Package archive, then install the
   specified NodeJS module.
  </dd>
 </dl>
</ul>

Installation:
<ul>
 <li>
  The <i>njs</i> plugin requires the presence of the <b>aptpkg</b> plugin.
 </li>
 <li>
  Should be installed after the <b>aptpkg</b> plugin.
 </li>
</ul>
</dd>

</dl>
