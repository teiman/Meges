<?php
 
header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");

$CabeceraXUL = '<#xml version="1.0" encoding="UTF-8"#>';
$CabeceraXUL .=	'<#xml-stylesheet href="chrome://global/skin/" type="text/css"#>';
$CabeceraXUL = str_replace("#","?",$CabeceraXUL);

echo $CabeceraXUL;

?>
<window id="login-meges" title="MeGes"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">       

<box id="root">
<button label="prueba"/>
</box>

<script><![CDATA[

var theString = "<box xmlns='http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul'><textbox id='hello' value='hola'/></box>";

var parser = new DOMParser();
var dom = parser.parseFromString(theString, "text/xml");

//alert( dom.getElementById("hello").getAttribute("value") );

dom.getElementById("hello").setAttribute("value","hola mundo");


var xroot = document.getElementById("root");

xroot.appendChild( dom.firstChild );




//]]></script>

</window>