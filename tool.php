<?php

session_start();

define ("FORCE",1);
define ("CORREO_ADMIN","9lands@gmail.com");
define ("TABLA_LISTADOS","men_listados");

//<VERSION PATCHING
if (!isset($PHP_SELF)){
	$PHP_SELF = $_SERVER["PHP_SELF"];
}
// VERSION PATCHING>

//SUPERGLOBALES
$action = $PHP_SELF;
	
if (isset( $_GET["modo"]))
	$modo = $_GET["modo"];
else if (isset( $_POST["modo"])){
	$modo = $_POST["modo"];
} else {
	$modo = false;
}

 
function g($tag="br",$txt ="", $clas="") {
	if($clas!="")
		$clas = " class=\"$clas\" ";
	
	return "<$tag $clas>$txt</$tag>";
}

function gColor($color,$txt,$bold=false){
	if(!$bold)
		return "<font color='$color'>$txt</font>";
	return "<font color='$color'><b>$txt</b></font>";
			
}

if (!function_exists("_")){

	function _($var){
		return $var;
	}
}



function error($donde,$texto=false){
	$donde = str_replace("\n"," ",$donde);
	$texto = str_replace("\n"," ",$texto);	
	error_log($donde . ": ". $texto , 0); 
}



$CabeceraXUL = '<#xml version="1.0" encoding="UTF-8"#>';
$CabeceraXUL .=	'<#xml-stylesheet href="chrome://global/skin/" type="text/css"#>';
//$CabeceraXUL .= '<#xml-stylesheet href="basecss.php" type="text/css"#>';

$CabeceraXUL = str_replace("#","?",$CabeceraXUL);

//Se usan en todas partes
require_once("db.php");
require_once("clean.inc.php");
require_once("comun.inc.php");
require_once("cursor.class.php");
require_once("supersesion.inc.php");




?>