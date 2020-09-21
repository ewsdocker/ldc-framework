
__Preliminary Documentation__ - 2020-08-28
____  
__plugins/aptpkg: The LDC (Linux Docker Container) Framework Plugins project.__  

<hr />

<ul>

<dl>
<dt>aptpkg - Install the requested packages.</dt>
 <dd>
 <i>apt-get</i> package installer. 
 

 <ul>
 <dl>
  <dt>pkgUpdate</dt>
   <dd>
   Initializes the pkgList buffer to "apt-get -y upgrade".
   </dd>
  <dt>pkgInstall</dt>
   <dd>
   Initializes the pkgList buffer to "apt-get -y install".
   </dd>
  <dt>pkgAddItem ${itemPkg} ${itemComment}</dt>
   <dd>
   Adds the specified package to the installation list.
   </dd>
  <dt>pkgExecute</dt>
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

<dt>nodejs</dt>
 <dd>
 Install the specified <i>NodeJS</i> repository and version. 
 
 <ul>
 <dl>
  <dt>njsInstallRepository</dt>
   <dd>
   Install the NodeJS Repository into the APT Repository.
   </dd>
  <dt>njsInstallJs ${updateRepository}</dt>
   <dd>
   The Repository is already installed in the APT, 
   install the selected NodeJS version and support packages.
   </dd>
  <dt>njsInstall ${NJS_URL} ${NJS_NAME}</dt>
   <dd>
   Install the specified NodeJS repository into the APT Package Repository 
   and the NodeJS version.
  </dd>
 </dl>
</ul>

Installation:
<ul>
 <li>
  The <i>njs</i> plugin requires the presence of the <i>aptpkg</i> plugin.
 </li>
 <li>
  Should be installed after the <i>aptpkg</i> plugin.
 </li>
</ul>
</dd>

</dl>

</ul>

<hr \>


