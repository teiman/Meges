<?php

require("tool.php");
require("users.inc.php");


switch($modo){
	case "alta":
		$nombre = CleanNombre($_POST["nombre"]);

		if ($id = CrearUsuario($nombre))
			echo "OK=$id";
		else
			echo "ERROR";
		exit();
		break;
		
	case "update":
		$nombre	 	= CleanNombre($_POST["nombre"]);
		$IdUsuario 	= CleanID($_POST["IdUsuario"]);		
		if ( ModificarUsuario( $IdUsuario, $nombre) ){
			echo "OK=$Idusuario";
		} else {
			echo "ERROR";
		}
		exit();
		break;
		
}



header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");
	
	
echo $CabeceraXUL;


?>
<window id="login-meges" title="meges"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">        
<hbox flex="1">
<groupbox flex="1">
<caption label="Listado"/>
<listbox  flex="1" id="listadoUsuarios">
</listbox>
</groupbox>
<groupbox flex="4">
<caption label="Gestion"/>
<hbox><button label="Nuevo usuario" oncommand="AddNewUser()"/><textbox id="nombreUsuarioAlta" value=""/></hbox>
<spacer style="height: 8px"/>
<groupbox>
<caption label="Editar"/>
<grid flex="1">
<rows> 
 <columns><column/></columns> 
<row><caption label="Nombre"/><textbox id="nombreUsuario"/></row>
<row><box/><button label="Modificar" oncommand="UpdateUser()"/></row>
</rows>
</grid>
</groupbox>
</groupbox>
</hbox>
<script>//<![CDATA[

function id(nombre) { return document.getElementById(nombre); };

var xlistadoUsuarios = id("listadoUsuarios");

function genRandomID() {
	return "R"+Math.random()*90000;	
}

//Agnade usuario en el interface
function xAddUser(IdUsuario, vnombreUsuarioAlta) {
	var xitem = document.createElement("listitem");
	var rid = "user_" + IdUsuario;
		
	xitem.setAttribute("label",vnombreUsuarioAlta);	
	xitem.setAttribute("id",rid);	
	xitem.setAttribute("onclick","EditarUser("+IdUsuario+", this)");	
	xlistadoUsuarios.appendChild(xitem);
}

//Intenta una alta de usuario, y si tiene exito, lo da de alta en las X.
function AddNewUser(){
	var vnombreUsuarioAlta = id("nombreUsuarioAlta").value;
	if ( vnombreUsuarioAlta.length < 1)
		return alert("Nombre demasiado corto!");		
	
	//Construimos envio de datos
	var data = "&nombre=" + escape(vnombreUsuarioAlta);
	var url = "formusers.php?modo=alta";
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){
		return alert("Datos no validos, pruebe otro nombre");
	}	
	
	xAddUser(resultado[1],vnombreUsuarioAlta);
	
	id("nombreUsuarioAlta").value  = "";
}

var editandoIdUsuario = 0;

function EditarUser(IdUsuario, xsender){
	//alert(IdUsuario+ sender.label);
	var xnombreUser = id("nombreUsuario");
	if (!xnombreUser)
		return alert("error: es necesario recargar la pagina");
	
	//xnombreUser.setAttribute("value",xsender.label);
	xnombreUser.value = xsender.getAttribute("label");
	editandoIdUsuario = IdUsuario;
}


//Intenta una alta de usuario, y si tiene exito, lo da de alta en las X.
function UpdateUser(){
	var vnombreUsuario = id("nombreUsuario").value;
	if ( vnombreUsuario.length < 1)
		return alert("Nombre demasiado corto!");		

	//Construimos envio de datos
	var data = "&nombre=" + escape(vnombreUsuario);
		data = data + "&IdUsuario=" + escape(editandoIdUsuario);
	var url = "formusers.php?modo=update";
			
	var ajax = new XMLHttpRequest();
	ajax.open("POST",url,false);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	ajax.send(data);
	resultado = ajax.responseText.split("=");
	if (resultado[0] != "OK"){	
		return alert("Datos no validos, pruebe otro nombre");
	}
		
	var usuarioNombreEnListado = id("user_"+ editandoIdUsuario);
	if (usuarioNombreEnListado){
		oldnombre	 = usuarioNombreEnListado.label;
		usuarioNombreEnListado.setAttribute("label",vnombreUsuario);
	}	
	
}

<?php

	$sql = "SELECT IdUsuario,nombre FROM men_usuarios WHERE Eliminado=0";
	$res = query($sql);
	if ($res) {
		while($row = Row($res)){
			$id = CleanID($row["IdUsuario"]);
			//Es formateado primero para xul, luego para ser incluido dentro de quote js
			$nombre = addslashes(CleanParaXul(CleanNombre($row["nombre"])));
			echo "xAddUser($id,'$nombre');\n";
		}
	}
	
?>

//]]></script>
</window>	