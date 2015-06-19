<?php

require("tool.php");
require("users.inc.php");




header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");


if (!$_SESSION["esAdminOperador"]){
	MiniPaginaMensaje("Este modulo requiere un administrador");	
	exit();
}
	
	
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
<caption label="Gestion" />

<hbox>
	<spacer flex="1"/><image style="width: 48px; height: 48px" src="img/toctoc.gif"/>
	<description style="font-size: 120%">GESTION DE USUARIOS</description>
	<spacer flex="1"/>
</hbox>

<groupbox id="CreandoNuevo">
<caption label="Nuevo"/>
<hbox>	
	<button image="img/addcliente.png" label="Nuevo usuario" oncommand="AddNewUser()"/>
	<textbox id="nombreUsuarioAlta" value=""/>	
	<spacer flex="1"/>
</hbox>
</groupbox>

<spacer style="height: 8px"/>

<groupbox id="ModificandoUsuario" pack="center">
<caption label="Editar"/>
<grid flex="1">
<rows> 
 <columns><column/></columns> 
<row><box/><checkbox id="activo" label="Cuenta activada"/></row>
<row><caption label="Nombre y apellidos"/><textbox id="nombreUsuario"/></row>
<row><caption label="Usuario"/><textbox id="usuario"/></row>
<row><caption label="Contraseña"/><textbox type="password" id="pass"/></row>
<row><box/><checkbox id="administrador" label="Administrador"/></row>
<row>
	<button image="img/borrarcliente.png" label="Eliminar" oncommand="BorrarUser()"/>
	<button image="img/modcliente.png" label="Modificar" oncommand="UpdateUser()"/></row>
</rows>
</grid>
</groupbox>

</groupbox>
</hbox>
<script type="application/x-javascript" src="ilumina.js?ver=1"/>
<script type="application/x-javascript" src="usuarios.js?ver=1"/>
<script>//<![CDATA[

<?php

GenerarJSUsers();

?>

//]]></script>
</window>	