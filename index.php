<?php

include("tool.php");


switch($modo){
	case "login":
		$sep = "#";
		
		$usuario 	= CleanText($_POST["usuario"]);
		$pass 		= CleanText($_POST["pass"]);		
		
		if (!$usuario or !$pass){
			echo "0";
			exit();
		}		
		
		$sql = "SELECT IdUsuario,esAdmin,Nombre FROM men_usuarios WHERE (usuario='$usuario') AND (pass = '$pass') AND Eliminado=0";
		$row = queryrow($sql);
		
		if (!$row) {
			echo "ERROR";
			exit();
		}
		
		echo $row["IdUsuario"] . $sep . $row["esAdmin"] . $sep . $row["Nombre"];
		
		$_SESSION["Status"] = "Logueado correctamente" . $row["Nombre"];
		
		setSesionDato("IdOperador",$row["IdUsuario"]);
		setSesionDato("esAdminOperador",$row["esAdmin"]);
		setSesionDato("NombreOperador",$row["Nombre"]);

		exit();
		break;


}

header("Content-Type: application/vnd.mozilla.xul+xml");
header("Content-languaje: es");

echo $CabeceraXUL;

?>
<window id="login-meges" title="gestion-online"
        xmlns:html="http://www.w3.org/1999/xhtml"        
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul" debug="false">       

<tabbox flex="1">
<tabs>
<tab image="img/producto16.png" id="tab-login" accesskey="n" label=" BIENVENIDO " collapsed="false"/>
<tab image="img/addcart.gif" id="tab-gastos" accesskey="G" label=" GASTOS " collapsed="true"/>
<tab image="img/cart.gif" id="tab-ingresos" accesskey="I" label=" INGRESOS " collapsed="true"/>
<tab image="img/attach.png" id="tab-documentos"  label=" DOCUMENTOS " collapsed="true"/>
<tab image="img/find16.png" id="tab-listados"  label=" LISTADOS " collapsed="true"/>
<tab image="img/cliente16.png" id="tab-users"  label=" USUARIOS " collapsed="true"/>
<tab image="img/keditbookmarks.png" id="tab-debug"  label=" SUGERENCIAS " collapsed="true"/>
</tabs>
<tabpanels flex="1" >
<tabpanel flex="1">
<hbox flex="1">
<spacer flex="1"/>
   <vbox flex="1">   
   <spacer flex="1"/>
	<groupbox style="width: 400px;height: 200px;background-color: #ECE8DE">
	 	<spacer flex="1"/>		
		<groupbox flex="1">
			<caption label="IDENTIFICACION" id="mensajebienvenida"/>			
			<hbox id="logout-box" collapsed="true" align="end" pack="end">
				<image style="width: 63px; height: 64px" src="img/crates64.gif"/>
				<spacer flex='1'/>
				<button image='img/exit.png'  label="Salir" oncommand="Logout()"/>
			</hbox>
			<grid id="grupo-login">
				<columns><column flex="1"/><column/></columns>
				<rows>
					<row>
						<description>Usuario</description>
						<textbox id='usuario' type="normal" onkeypress="if (event.which == 13) document.getElementById('passlocal').focus()"/>
					</row>
					<row>
						<description>Contraseña</description>
						<textbox id='pass' type="password" onkeypress="if (event.which == 13) { Login() }"/>
					</row>
					<row>					
						<box>
							<image style="width: 48px; height: 48px" src="img/toctoc.gif"/>
							<caption id="mensaje-nombre" label=""/>
						</box>                                                
                                        <button  flex='1' label="Entrar" oncommand="Login()"/>                                                                                        
					</row>					
				</rows>												
			</grid>			
		</groupbox>
		<spacer flex="1"/>		
	</groupbox>
	<spacer flex="1"/>	 
	</vbox>
    <spacer flex="1"/>
</hbox>    
</tabpanel>
<iframe id="gastos" src="about:blank"/>
<iframe id="ingresos" src="about:blank"/>
<iframe id="documentos" src="about:blank" collapsed="true"/>
<iframe id="listados" src="about:blank"/>
<iframe id="users" src="about:blank"/>
<iframe id="debug" src="about:blank"/>
</tabpanels>
</tabbox>
<script type="application/x-javascript" src="main.js?ver=1"/>


</window>